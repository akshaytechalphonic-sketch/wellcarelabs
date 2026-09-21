@extends('maindesign')

@section('title', 'Privacy Policy & Terms & Condition - Wellcare Labs')

@section('content')

<style>
  .wc-page-wrapper {
    max-width: 1500px;
    margin: 40px auto;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
  }

  /* Main Heading – same style as "Wellcare Appointment Form" */
  .wc-page-wrapper h1 {
    font-size: 2.4rem;
    font-weight: 700;
    color: #0a2540;
    text-align: center;
    position: relative;
    margin-bottom: 35px;
  }

  .wc-page-wrapper h1::after {
    content: "";
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #0047ff, #00ccff);
    border-radius: 3px;
    display: block;
    margin: 12px auto 0;
  }

  /* Section headings */
  .wc-page-wrapper h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0a2540;
    margin-top: 28px;
    margin-bottom: 10px;
  }

  .wc-page-wrapper p,
  .wc-page-wrapper ul li {
    font-size: 15px;
    line-height: 1.7;
    color: #000000;
    margin-top: 10px;
  }

  .wc-page-wrapper ul {
    padding-left: 20px;
    margin-top: 5px;
  }

  @media (max-width: 768px) {
    .wc-page-wrapper {
      margin: 15px;
      padding: 22px;
    }

    .wc-page-wrapper h1 {
      font-size: 1.9rem;
    }
  }
</style>

<section class="wc-page-wrapper">

  <h1>Terms & Conditions and Privacy Policy</h1>

  <p>
    This Terms, Conditions and Privacy Policy outlines our commitments to respecting your online privacy and ensuring
    the appropriate protection and management of any Personal Information you share with us. By using our Website
    (www.Wellcarelabs.in), you agree to be legally bound by this Privacy Policy.
  </p>

  <h2>1. Scope of the Privacy Policy</h2>
  <p>
    This policy helps you understand the guidelines and procedures followed by the Company when collecting, storing,
    utilizing, and disclosing your Personal Information. Your first use of the Website confirms your acceptance of this
    Policy.
  </p>

  <h2>2. Information Collection</h2>
  <p>
    We collect Personal Information provided during account registration, lab test bookings, and communication. This
    information helps us provide efficient services and a safer experience.
  </p>
  <p>
    You may browse the Website without sharing personal information. However, for registration and booking, some
    details must be shared. Mandatory fields are marked (*). Optional fields are your choice.
  </p>
  <p>We may collect information through:</p>
  <ul>
    <li>Registration and booking forms</li>
    <li>Website usage analytics</li>
    <li>Cookies to improve page experience and reliability</li>
    <li>Emails, documents and communication records</li>
  </ul>

  <h2>3. Use of Personal Information</h2>
  <p>We may use your information to:</p>
  <ul>
    <li>Provide lab testing and diagnostic services</li>
    <li>Maintain medical history for better diagnosis</li>
    <li>Improve our Website, features and performance</li>
    <li>Customize and enhance your user experience</li>
    <li>Send updates, reminders, offers and important alerts</li>
  </ul>

  <h2>4. Information We Collect & Do Not Share</h2>
  <p>Personal data collected may include (but is not limited to):</p>
  <ul>
    <li>Name, email address, postal address, phone number</li>
    <li>Location and device information</li>
    <li>Medical records, prescriptions, reports and uploads</li>
    <li>Crash logs, diagnostics and performance data</li>
  </ul>
  <p>
    We DO NOT sell or rent your Personal Information. We may share your information only under the following
    circumstances:
  </p>
  <ul>
    <li>With authorized doctors, dieticians and healthcare professionals for diagnosis</li>
    <li>With third-party payment providers and billing partners for payment processing</li>
    <li>To comply with legal obligations, court orders or requests from government authorities</li>
    <li>To share anonymized, aggregated statistics without revealing your identity</li>
  </ul>

  <h2>5. Payment Information Security</h2>
  <p>
    We do not store any payment card details on our servers. All online transactions are processed securely through
    trusted third-party payment gateways. While we ensure best practices from our end, the Company is not liable for any
    loss arising from issues occurring at the payment gateway or due to unauthorized use of your payment instruments
    outside our systems.
  </p>

  <h2>Contact Us</h2>
  <p>
    <i class="fa-solid fa-envelope" style="margin-right:6px;color:#0047ff;"></i>
    <strong>Email:</strong>
    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=support@wellcarelabs.in" target="_blank" rel="noopener noreferrer">
      <span class="mai-mail text-primary"></span> support@wellcarelabs.in
    </a>
    <br>

    <i class="fa-solid fa-phone" style="margin-right:6px;color:#0047ff;"></i>
    <strong>Phone:</strong>
    <a href="tel:+919158980898" style="color:#0047ff; text-decoration:underline;">
      +91 915 898 0898
    </a>
  </p>

  <h2>Office Address</h2>
  <p>
    <i class="fa-solid fa-location-dot" style="margin-right:6px;color:#0047ff;"></i>
    Shop 113, A-Wing, Sai Vision Mall,<br>
    Kunal Icon Road, Pimple Saudagar,<br>
    Pimpri-Chinchwad, Pune,<br>
    Maharashtra - 411027
  </p>

</section>

@endsection
