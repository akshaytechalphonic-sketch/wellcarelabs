{{-- resources/views/about.blade.php --}}
@extends('maindesign')

@section('title', 'About Us - Wellcare Labs')

@section('content')



<div style="text-align:center;margin-top:20px;margin-bottom:30px;">
  <h2 class="h3 mb-2 fw-bold" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px;">
      Wellcare<span style="color:#0d6efd;"> About Us</span>
    </h2>
    <div style="width:80px;height:4px;margin:10px auto 16px;border-radius:3px;
                background:linear-gradient(90deg,#0047ff,#00ccff);"></div>


        <h1 class="mb-3" style="font-size:2.2rem;font-weight:700;color:#0a2540;margin-bottom:10px">Welcome to Your Health Center</h1>

</div>





 

  <div class="page-section">
  <div class="container">
    <div class="row align-items-center">

          <!-- Right Side (Image) -->
      <div class="col-lg-6 text-center wow fadeInUp" data-wow-delay="0.2s">
        <img src="{{ asset('Front_end/assets/img/about_img_2.png') }}" 
             alt="About Wellcare Labs" 
             class="img-fluid rounded shadow">
      </div>
      
      <!-- Left Side (About Us Content) -->
      <div class="col-lg-6 wow fadeInUp">
        <h3 class="mb-4" style="color:#007bff;">“Where <strong>accuracy</strong> meets <strong>care</strong> for better health.”</h3>
        <div class="text-lg">
          <p style="color:#000;">
            Welcome to <strong>Wellcare Labs</strong> – your trusted partner in 
            accurate and reliable pathology testing. With <strong>ISO certification</strong>, 
            advanced technology, and expert professionals, we ensure accurate results with 
            <strong>on-time reporting</strong>. At Wellcare Labs, we combine precision with care, 
            because your health deserves nothing less.
          </p>
          <p style="color:#000;">
            At Wellcare Labs, we believe that <strong>healthcare begins with accurate diagnostics</strong>. 
            As a trusted name in pathology, we are committed to delivering accuracy, reliability, 
            and care in every report. Our ISO-certified processes ensure the highest standards of 
            quality, safety, and efficiency, giving our patients and healthcare partners complete 
            confidence in the results.
          </p>
          <p style="color:#000;">
            With advanced technology, skilled professionals, and a strong focus on precision, 
            we provide a wide range of diagnostic services to support the <strong>early detection, 
            prevention, and treatment of diseases</strong>. We take pride in our timely reporting, 
            because we understand that results delivered on time can make all the difference in 
            effective medical care.
          </p>
          <p style="color:#000;">
            At Wellcare Labs, <strong>accuracy meets empathy</strong>—we go beyond just numbers 
            and reports, ensuring that every test contributes to <strong>better health 
            and well-being</strong>.
          </p>
        </div>
      </div>

      <!-- Right Side (Image) -->
      {{-- <div class="col-lg-6 text-center wow fadeInUp" data-wow-delay="0.2s">
        <img src="{{ asset('Front_end/assets/img/about_img_2.png') }}" 
             alt="About Wellcare Labs" 
             class="img-fluid rounded shadow">
      </div> --}}

    </div>
  </div>
</div>





<!-- Our Mission -->

<div class="page-section pb-0 section-bg">
  <div class="container">
    <div class="row align-items-stretch">
      <!-- IMAGE on left -->
      <div class="col-lg-6 wow fadeInLeft" data-wow-delay="400ms">
        <div class="img-place custom-img-1">
          <img src="{{ asset('Front_end/assets/img/healthcare1.png') }}"
               alt="Healthcare" class="mission-img">
        </div>
      </div>

      <!-- TEXT on right -->
      <div class="col-lg-6 py-3 wow fadeInUp d-flex align-items-center">
        <div>
          <h1  class="mb-3" style="font-weight:700;">🌿 Wellcare Mission</h1>

            <h3 class="mb-4" style="color:#007bff; ">“Precision in every test, care in every step.”</h3>
        
          
          <p class="mb-4" style="color:#000;">
            To make healthcare simple, trustworthy, and accessible by offering precise and timely diagnostic services 
            that help you and your loved ones stay healthy.
                      At Wellcare Labs, our mission is to make healthcare <strong>accurate, accessible, and compassionate</strong> for everyone. 
          We are dedicated to delivering precise diagnostic services that empower doctors and patients with the confidence 
          to make informed healthcare decisions. By combining <strong>cutting-edge technology, skilled professionals, and ISO-certified processes</strong>, 
          we ensure every test meets the highest standards of quality and reliability. Beyond just reports, we believe in building 
          <strong>trust through care, empathy, and integrity</strong>, because at Wellcare, every result is more than a number—it’s a step towards 
          healthier lives and stronger communities.

          </p>
        </div>
      </div>
    </div>
  </div>
</div> <!-- .page-section -->




<!-- Our Vision -->
<div class="page-section pb-0 section-bg">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 py-3 wow fadeInUp">
        <h1  class="mb-3" style="font-weight:700;">Wellcare Vision</h1>
         <h3 class="mb-4" style="color:#007bff;">“Wellcare – Inspiring trust, shaping healthier lives.”</h3>
        <p class="mb-4" style="color:#000;">
          To be the most trusted name in diagnostics where accuracy meets empathy, ensuring every patient feels cared for, confident, and supported on their health journey.
         At Wellcare Labs, our vision is to be the most trusted name in diagnostics, where 
          <strong>accuracy meets empathy</strong>. We strive to transform healthcare by delivering 
          reliable and timely results that empower patients and doctors to make confident decisions. 
          Guided by innovation, compassion, and a commitment to excellence, Wellcare envisions a 
          future where every individual has access to world-class diagnostics, ensuring healthier 
          communities and a brighter tomorrow.
        
        </p>
        
      </div>
      <div class="col-lg-6 wow fadeInRight" data-wow-delay="400ms">
        <div class="img-place custom-img-1">
          <img src="{{ asset('Front_end/assets/img/bg-doctor.png') }}" alt="">
        </div>
      </div>
    </div>
  </div>
</div>





<!-- WHY CHOOSE US - Diagnostic Centre -->
<div style="background:#f8fafc; padding:90px 20px 60px 20px; width:100%;">
  <h2 style="font-size:2rem; font-weight:700; margin-bottom:15px; text-align:center;">
    Why Choose <span style="color:#0d6efd;">Wellcare Labs</span>
  </h2>
  <p style="font-size:1rem; color:#555; max-width:800px; margin:0 auto 40px auto; text-align:center; line-height:1.7;">
    We combine cutting-edge technology with experienced professionals to deliver 
    reliable results that empower better healthcare decisions. Here’s why patients 
    and doctors trust us every day:
  </p>

  <!-- Features grid -->
  <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; max-width:1100px; margin:0 auto;">
    
    

    <!-- Feature 2 -->
    <div style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
      <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">⏱️</div>
              <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ 8+ years of trusted excellence in diagnostics
        </h3>

              <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ ISO-certified for quality and reliability

        </h3>
          <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">
        ✅ 100% accurate & timely reports
        </h3>
          
            </div>

    <!-- Feature 3 -->
    <div style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
      <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">✅</div>
      <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Advanced technology & modern equipment </h3>
          <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">  ✅ Expert pathologists & skilled technicians </h3>
        <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;"> ✅ Wide range of health check-ups & tests
        </h3>
              
            </div>

    <!-- Feature 4 -->
    <div style="background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 16px rgba(0,0,0,0.05); text-align:center;">
      <div style="font-size:2rem; color:#0d6efd; margin-bottom:12px;">🤝</div>
      <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Convenient home sample collection</h3>
       <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Online report access anytime, anywhere</h3>
        <h3 style="font-size:1.2rem; font-weight:600; margin-bottom:8px;">✅ Patient-friendly care with a human touch</h3>
      
    </div>

  </div>
</div>








<!-- Wellcare Team Section -->
{{-- <div class="page-section">
  <div class="container">
    <div class="col-lg-10 mx-auto mt-5">
      
      <h1 class="text-center mb-3 wow fadeInUp" style="font-weight:700; color:#000;">
        Wellcare Team
      </h1>
      <h3 class="text-center mb-5" style="color:#007bff; font-weight:500;">
        “Dedicated experts bringing <strong>care</strong> and <strong>compassion</strong> to every patient.”
      </h3>

      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4 wow zoomIn">
          <div class="card-doctor">
            <div class="header">
              <img src="{{ asset('Front_end/assets/img/doctors/doctor_1.jpg') }}" alt="">
              <div class="meta">
                <a href="#"><span class="mai-call"></span></a>
                <a href="#"><span class="mai-logo-whatsapp"></span></a>
              </div>
            </div>
            <div class="body">
              <p class="text-xl mb-0">Dr. Stein Albert</p>
              <span class="text-sm text-grey">Cardiology</span>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 wow zoomIn">
          <div class="card-doctor">
            <div class="header">
              <img src="{{ asset('Front_end/assets/img/doctors/doctor_2.jpg') }}" alt="">
              <div class="meta">
                <a href="#"><span class="mai-call"></span></a>
                <a href="#"><span class="mai-logo-whatsapp"></span></a>
              </div>
            </div>
            <div class="body">
              <p class="text-xl mb-0">Dr. Alexa Melvin</p>
              <span class="text-sm text-grey">Dental</span>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 wow zoomIn">
          <div class="card-doctor">
            <div class="header">
              <img src="{{ asset('Front_end/assets/img/doctors/doctor_3.jpg') }}" alt="">
              <div class="meta">
                <a href="#"><span class="mai-call"></span></a>
                <a href="#"><span class="mai-logo-whatsapp"></span></a>
              </div>
            </div>
            <div class="body">
              <p class="text-xl mb-0">Dr. Rebecca Steffany</p>
              <span class="text-sm text-grey">General Health</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div> --}}

<!-- App Banner Section -->
<!-- Wellcare Closing Banner -->










        

   
@endsection
