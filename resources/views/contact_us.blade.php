@extends('maindesign')

@section('title', isset($page) && !empty($page->meta_title) ? $page->meta_title : 'Contact Wellcare Labs – Diagnostic & Health Test Support | Pune')
@section('meta_description', isset($page) && !empty($page->meta_description) ? $page->meta_description : 'Get in touch with Wellcare Labs for appointments, diagnostic test inquiries, and support. Contact via phone +91 9158980898 or email info@wellcarelabs.in for reliable health services and 24×7 assistance.')

@section('seo')
    @if(isset($page) && !empty($page->meta_tags))
        {!! $page->meta_tags !!}
    @endif

@endsection

@push('head')
    <style>
        /* ---------- Page container ---------- */
        .contact-page {
            background: #f8fafc;
            padding: 20px 20px;
            width: 100%;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* center grid wrapper */
        .contact-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
        }

        /* header area */
        .contact-hero {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 8px;
        }

        .contact-hero .h2-like {
            font-size: 2.2rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 10px;
            line-height: 1.05;
        }

        .contact-hero .h2-like span {
            color: #0d6efd;
        }

        /* header underline */
        .contact-hero .accent-line {
            width: 80px;
            height: 4px;
            margin: 10px auto 16px;
            border-radius: 3px;
            background: linear-gradient(90deg, #0047ff, #00ccff);
        }

        /* grid for contact info + form */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
            align-items: start;
        }

        /* card basics */
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 6px 16px rgba(16, 24, 40, 0.06);
            transition: transform .18s ease, box-shadow .18s ease;
            box-sizing: border-box;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08);
        }

        /* left column (contact info) */
        .contact-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 16px;
            color: #0a2540;
        }

        .contact-info p {
            margin: 0 0 12px 0;
            color: #374151;
            font-size: 0.96rem;
            line-height: 1.5;
        }

        .contact-info strong {
            color: #0a2540;
        }

        /* form column */
        .contact-form-wrapper .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 18px;
            text-align: center;
        }

        /* form layout tweaks */
        .contact-form {
            display: block;
            width: 100%;
        }

        /* NAME+EMAIL - side-by-side on larger screens; wrap/stack on small screens (no scrollbar) */
        .name-email-row {
            display: flex;
            gap: 12px;
            align-items: stretch;
            flex-wrap: wrap;
            padding-bottom: 6px;
        }

        .name-email-row>.field {
            flex: 1 1 50%;
            min-width: 0;
            box-sizing: border-box;
        }

        /* On small screens stack full width */
        @media (max-width: 575px) {
            .name-email-row>.field {
                flex: 1 1 100%;
            }
        }

        /* other fields full width */
        .field-full {
            width: 100%;
            box-sizing: border-box;
            margin-top: 12px;
        }

        /* exact height */
        #message {
            min-height: 90px;
            height: 90px;
            max-height: 300px;
            resize: vertical;
        }

        /* inputs */
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #e6e9ee;
            background: #fff;
            font-size: 0.95rem;
            color: #0f1724;
            transition: border-color .15s ease, box-shadow .12s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }

        /* textarea */
        .form-control[rows] {
            min-height: 140px;
            resize: vertical;
        }

        /* invalid feedback */
        .invalid-feedback {
            color: #dc2626;
            font-size: 0.9rem;
            margin-top: 6px;
            display: block;
        }

        /* submit button */
        #contactSubmit {
            background: linear-gradient(180deg, #0047ff, #0066ff);
            color: #fff;
            border: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            box-shadow: 0 10px 22px rgba(0, 71, 255, 0.12);
            transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
        }

        #contactSubmit[disabled] {
            opacity: .7;
            transform: translateY(0);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
        }

        /* small privacy note */
        .small-note {
            font-size: 0.9rem;
            color: #6b7280;
            margin-top: 10px;
        }

        /* ajax alert (fallback) */
        #contact-alert {
            width: 100%;
            text-align: center;
            padding: 10px 14px;
            border-radius: 8px;
            box-sizing: border-box;
        }

        /* Customize SweetAlert confirm button to match Wellcare gradient */
        .swal2-confirm {
            background: linear-gradient(135deg, #0066ff, #00ccff) !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 12px 26px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            box-shadow: 0 5px 18px rgba(0, 102, 255, 0.32) !important;
        }

        .swal2-confirm:hover {
            opacity: 0.9 !important;
            transition: opacity .2s ease-in-out;
        }

        .swal2-cancel {
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 12px 26px !important;
        }



        /* responsive tweaks */
        @media (max-width: 991px) {
            .contact-page {
                padding: 28px 16px;
            }

            .contact-hero .h2-like {
                font-size: 1.9rem;
            }

            .contact-inner {
                gap: 20px;
            }

            .contact-grid {
                gap: 20px;
            }
        }
    </style>
@endpush

@section('content')

    <!-- CONTACT US PAGE -->
    <div class="contact-page">
        <div class="contact-inner">

            <!-- Heading -->
            <div class="contact-hero">
                <h1 class="h2-like">Contact <span>Wellcare Labs </span></h1>
                <div class="accent-line" aria-hidden="true"></div>
                <p class="text-muted" style="font-size:1.05rem;color:#6b7280;margin:0;">
                    Have a question, need an appointment, or want to know more about our services? Reach out to us — we’re
                    here to help.
                </p>
            </div>

            <div class="contact-grid">

                <!-- Contact Info (LEFT column) -->
                <div class="card contact-info">
                    <h3>Get in Touch</h3>
                    <p>
                        📍 <strong>Address:</strong><br>
                        Wellcare Labs ,<br>
                        Shop 113, First floor, A-Wing, Sai vision Mall, Pimple Saudagar,
                        Pimpri-Chinchwad, Pune, Maharashtra 411027.
                    </p>
                    <p>📞 <strong>Phone:</strong> +91 9158980898</p>
                    <p>✉️ <strong>Email:</strong> info@wellcarelabs.in</p>
                    <p>🕒 <strong>Operating Timings:-</strong> 24x7 Hours</p>
                </div>

                <!-- Form Column (RIGHT) -->
                <div class="card contact-form-wrapper">
                    <h3 class="form-title">Connect With WellCare Labs</h3>

                    {{-- Fallback: Laravel validation errors / success (non-AJAX) --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="contactFormAjax" action="{{ route('inquiry.store') }}" method="POST" class="contact-form"
                        enctype="multipart/form-data" role="form" aria-label="Contact form" novalidate>
                        @csrf

                        {{-- Honeypot (spam trap) --}}
                        <div style="position:absolute; left:-5000px; width:1px; height:1px; overflow:hidden;">
                            <label for="hp_verification_check">Verification Check</label>
                            <input type="text" id="hp_verification_check" name="hp_verification_check" tabindex="-1"
                                autocomplete="new-password">
                        </div>

                        <!-- AJAX / dynamic alert (fallback only) -->
                        <div id="contact-alert" class="mb-3" style="display:none" role="status" aria-live="polite"></div>

                        <!-- NAME + EMAIL -->
                        <div class="name-email-row" aria-label="Name and email">
                            <div class="field">
                                <label for="fullName">Name <span class="text-danger">*</span></label>
                                <input type="text" id="fullName" name="name"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Full name.."
                                    required value="{{ old('name') }}" aria-describedby="nameHelp" aria-required="true">
                                <div id="nameHelp" class="invalid-feedback" data-field="name">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>

                            <div class="field">
                                <label for="emailAddress">Email <span class="text-danger"></span></label>
                                <input type="email" id="emailAddress" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email address (Optional).." value="{{ old('email') }}"
                                    aria-describedby="emailHelp" aria-required="false">
                                <div id="emailHelp" class="invalid-feedback" data-field="email">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="field-full" style="margin-top:12px;">
                            <!-- Phone -->
                            <label for="phoneNumber">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" id="phoneNumber" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Enter phone number (10 digits).." value="{{ old('phone') }}"
                                aria-describedby="phoneHelp" required>
                            <div id="phoneHelp" class="invalid-feedback" data-field="phone">
                                @error('phone')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>
                        <div class="field-full" style="margin-top:12px;">
                            <label for="prescriptionUpload">Upload Prescription</label>
                            <input type="file" name="prescription" id="prescriptionUpload"
                                class="form-control @error('prescription') is-invalid @enderror"
                                aria-describedby="prescriptionHelp">
                            <div id="prescriptionHelp" class="invalid-feedback" data-field="prescription">
                                @error('prescription')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div class="field-full" style="margin-top:12px;">
                            <!-- Message -->
                            <label for="message">Message </label>
                            <textarea id="message" name="message"
                                class="form-control @error('message') is-invalid @enderror" rows="6"
                                placeholder="Enter message.." required
                                aria-describedby="messageHelp">{{ old('message') }}</textarea>
                            <div id="messageHelp" class="invalid-feedback" data-field="message">
                                @error('message')
                                    {{ $message }}
                                @enderror
                            </div>
                        </div>

                        <div style="margin-top:18px;">
                            <!-- Submit -->
                            <button id="contactSubmit" type="submit" class="wow zoomIn" aria-live="polite"
                                aria-busy="false">
                                <span id="contactSubmitText">Submit</span>
                                <span id="contactSpinner" class="spinner-border spinner-border-sm" role="status"
                                    aria-hidden="true" style="display:none;margin-left:8px;"></span>
                            </button>
                        </div>

                        <div class="small-note mt-3 text-center text-muted">
                            We respect your privacy. We'll never share your info.
                        </div>

                        <noscript>
                            <div class="alert alert-info mt-3" role="alert">
                                JavaScript is disabled — the form will submit with a page reload and show validation
                                messages above.
                            </div>
                        </noscript>
                    </form>
                </div>
                <!-- /Form Column -->

            </div>
        </div>
    </div>

    {{-- 🔹 Load SweetAlert2 directly on this page --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- AJAX + Client-side Validation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('contactFormAjax');
            if (!form) return;

            const submitBtn = document.getElementById('contactSubmit');
            const submitText = document.getElementById('contactSubmitText');
            const spinner = document.getElementById('contactSpinner');
            const alertBox = document.getElementById('contact-alert');

            const namePattern = /^[A-Za-z\s]+$/;
            const phonePattern = /^[0-9]{10}$/;

            function resetErrors() {
                if (alertBox) {
                    alertBox.style.display = 'none';
                    alertBox.innerHTML = '';
                    alertBox.className = '';
                }
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
                if (submitBtn) {
                    submitBtn.removeAttribute('disabled');
                    submitBtn.setAttribute('aria-busy', 'false');
                }
                if (submitText) submitText.innerText = 'Submit';
                if (spinner) spinner.style.display = 'none';
            }

            // ✅ Main popup – like your screenshot, with Thank you + OK + auto-close
            function showAlert(type, msg) {
                if (window.Swal) {
                    if (type === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you!',
                            text: msg ||
                                'Thank you for connecting with Wellcare Labs Our Health Advisor will call you shortly.',
                            showCancelButton: false,
                            width: '350px',
                            confirmButtonText: 'OK',
                            timer: 10000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: msg || 'Something went wrong. Please try again.',
                            width: '350px',
                            confirmButtonText: 'OK'
                        });
                    }
                    return;
                }

                // fallback (only if SweetAlert fails to load)
                if (alertBox) {
                    alertBox.style.display = 'block';
                    alertBox.className = 'alert ' + (type === 'success' ? 'alert-success' : 'alert-danger');
                    alertBox.innerText = msg;
                    return;
                }

                alert(msg || (type === 'success' ?
                    'Your message has been sent.' :
                    'Something went wrong.'));
            }

            function setLoading(isLoading) {
                if (!submitBtn || !submitText || !spinner) return;
                if (isLoading) {
                    submitBtn.setAttribute('disabled', 'disabled');
                    submitBtn.setAttribute('aria-busy', 'true');
                    submitText.innerText = 'Sending...';
                    spinner.style.display = 'inline-block';
                } else {
                    submitBtn.removeAttribute('disabled');
                    submitBtn.setAttribute('aria-busy', 'false');
                    submitText.innerText = 'Submit';
                    spinner.style.display = 'none';
                }
            }

            function applyFieldError(fieldName, message) {
                const input = form.querySelector(`[name="${fieldName}"]`);
                const feedback = form.querySelector(`.invalid-feedback[data-field="${fieldName}"]`);
                if (input) input.classList.add('is-invalid');
                if (feedback) feedback.innerText = message;
            }

            function clientValidate() {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');

                const name = (form.name.value || '').trim();
                const email = (form.email.value || '').trim();
                const phone = (form.phone.value || '').trim();
                const message = (form.message.value || '').trim();
                // const website = (form.hp_verification_check ? form.hp_verification_check.value : '').trim(); // honeypot
                const website = document.getElementById('hp_verification_check').value.trim();

                if (website.length > 0) {
                    showAlert('error', 'Spam detected.');
                    return {
                        valid: false,
                        reason: 'honeypot'
                    };
                }

                if (!name) {
                    applyFieldError('name', 'Please enter your full name.');
                } else if (!namePattern.test(name)) {
                    applyFieldError('name', 'Name should only contain letters and spaces.');
                } else if (name.length > 255) {
                    applyFieldError('name', 'Name must not exceed 255 characters.');
                }

                const emailInput = form.email;
                if (email && email.length > 255) {
                    applyFieldError('email', 'Email must not exceed 255 characters.');
                }


                if (!phone) {
                    applyFieldError('phone', 'Please enter your phone number.');
                } else if (!phonePattern.test(phone)) {
                    applyFieldError('phone', 'Please enter a valid 10-digit phone number.');
                } else if (phone.length > 50) {
                    applyFieldError('phone', 'Phone must not exceed 50 characters.');
                }

                if (!message) {
                    applyFieldError('message', 'Please enter your message.');
                } else if (message.length > 2000) {
                    applyFieldError('message', 'Message must not exceed 2000 characters.');
                }

                const anyErrors = Array.from(form.querySelectorAll('.invalid-feedback'))
                    .some(fe => fe.innerText.trim() !== '');
                return {
                    valid: !anyErrors
                };
            }

            async function safeParseJSON(response) {
                const contentType = response.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') !== -1) {
                    try {
                        return await response.json();
                    } catch (err) {
                        console.warn('Failed to parse JSON:', err);
                        return null;
                    }
                }
                try {
                    const txt = await response.text();
                    return {
                        __rawText: txt
                    };
                } catch (err) {
                    return null;
                }
            }

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                resetErrors();

                const {
                    valid,
                    reason
                } = clientValidate();
                if (!valid) {
                    if (reason === 'honeypot') return;
                    showAlert('error', 'Please correct the highlighted fields and try again.');
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) firstInvalid.focus();
                    return;
                }

                setLoading(true);

                const action = form.getAttribute('action');
                const formData = new FormData(form);

                try {
                    const res = await fetch(action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    const data = await safeParseJSON(res);

                    if (res.ok) {
                        showAlert(
                            'success',
                            (data && (data.message || (data.success && data.message))) ||
                            'Thank you for connecting with Wellcare Labs Our Health Advisor will call you shortly.'
                        );
                        form.reset();
                    } else if (res.status === 422 && data && data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = form.querySelector(`[name="${field}"]`);
                            const feedback = form.querySelector(
                                `.invalid-feedback[data-field="${field}"]`);
                            if (input) input.classList.add('is-invalid');
                            if (feedback) feedback.innerText =
                                Array.isArray(data.errors[field]) ? data.errors[field].join(
                                    ' ') : String(data.errors[field]);
                        });
                        showAlert('error', 'Please correct the highlighted fields and try again.');
                        const firstInvalid = form.querySelector('.is-invalid');
                        if (firstInvalid) firstInvalid.focus();
                    } else if (res.status === 419) {
                        showAlert('error',
                            'Session expired (CSRF). Please refresh the page and try again.');
                    } else {
                        if (data && data.message) {
                            showAlert('error', data.message);
                        } else if (data && data.__rawText) {
                            console.error('[ContactForm] Raw response:', data.__rawText);
                            showAlert('error', 'Server error. Please try again later.');
                        } else {
                            showAlert('error', 'Something went wrong. Please try again later.');
                        }
                    }
                } catch (err) {
                    console.error('[ContactForm] Fetch or parse error:', err);
                    showAlert('error', 'Network or CORS error. Please try again.');
                } finally {
                    setLoading(false);
                }
            });
        });
    </script>

@endsection

@push('scripts')
    <script src="{{ asset('Front_end/assets/js/google-maps.js') }}"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIA_zqjFMsJM_sxP9-6Pde5vVCTyJmUHM&callback=initMap"></script>
@endpush