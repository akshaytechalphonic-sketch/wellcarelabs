<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        // STATUS FILTER: ?status=active | inactive | (empty => all)
        $status = $request->get('status');

        if ($status === 'active') {
            $query->where('is_active', 1);
        } elseif ($status === 'inactive') {
            $query->where('is_active', 0);
        }

        // Sorting – newest first (change if you later add an order_no column)
        $query->latest();

        // Use pagination; your Blade already supports paginator or collection
        $banners = $query->paginate(20)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'     => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'required|boolean',
            'url' => 'nullable|url|max:255',
        ]);

        $file = $request->file('image');

        // Compute md5 hash of the actual file contents
        $hash = md5_file($file->getRealPath());

        // If a banner with the same image already exists, block it
        if (Banner::where('image_hash', $hash)->exists()) {
            $msg = ['image' => ['This banner image has already been uploaded.']];
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $msg], 422);
            }
            return back()->withErrors($msg)->withInput();
        }

        // Store the image
        $filename = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('banners', $filename, 'public');

        // Save banner
        $banner = new Banner();
        $banner->image = $path;
        $banner->image_hash = $hash;
        $banner->is_active = $request->boolean('is_active');
        $banner->url = $request->input('url');
        $banner->save();
        \Illuminate\Support\Facades\Cache::forget('homepage_active_banners');

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $rules = [
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_active' => 'required|boolean',
            'url' => 'nullable|url|max:255',
        ];

        $messages = [
            'image.image'        => 'Uploaded file must be an image.',
            'image.mimes'        => 'Image must be a file of type: jpg, jpeg, png, webp.',
            'image.max'          => 'Image may not be greater than 5MB.',
            'is_active.required' => 'Active status is required.',
            'is_active.boolean'  => 'Active status must be true or false.',
            'url.url' => 'The URL must be a valid URL.',
            'url.max' => 'The URL may not be greater than 255 characters.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['url'] = $request->input('url');

        // If a new image is uploaded, check for duplicates and replace
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $hash = md5_file($file->getRealPath());

            // Exclude the current banner id during duplicate check
            $duplicateExists = Banner::where('image_hash', $hash)
                ->where('id', '!=', $banner->id)
                ->exists();

            if ($duplicateExists) {
                $msg = ['image' => ['This banner image has already been uploaded.']];
                if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
                    return response()->json(['success' => false, 'errors' => $msg], 422);
                }
                return back()->withErrors($msg)->withInput();
            }

            // Delete old file if exists
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            // Store new file
            $data['image'] = $file->store('banners', 'public');
            $data['image_hash'] = $hash;
        }

        $banner->update($data);
        \Illuminate\Support\Facades\Cache::forget('homepage_active_banners');

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        \Illuminate\Support\Facades\Cache::forget('homepage_active_banners');

        return redirect()->route('admin.banners.index')
            ->with('danger', 'Banner deleted.');
    }

    public function toggleStatus(Banner $banner)
    {
        $banner->is_active = ! $banner->is_active;
        $banner->save();
        \Illuminate\Support\Facades\Cache::forget('homepage_active_banners');

        return response()->json([
            'is_active' => (int) $banner->is_active,
        ]);
    }

    public function show(Banner $banner)
    {
        if (request()->wantsJson()) {
            return response()->json(['data' => $banner]);
        }

        return view('admin.banners.show', compact('banner'));
    }
}
