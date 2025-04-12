@extends('layouts.master')
@section('title', 'Privacy Policy')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Privacy Policy</h1>
            <p class="card-text">Last updated: {{ now()->format('Y-m-d') }}</p>

            <h2>Information We Collect</h2>
            <p>When you use our application, we collect the following information:</p>
            <ul>
                <li>Your name and email address when you register</li>
                <li>Profile information from LinkedIn when you sign in with LinkedIn</li>
                <li>Profile information from Google when you sign in with Google</li>
            </ul>

            <h2>How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Provide and maintain our service</li>
                <li>Notify you about changes to our service</li>
                <li>Provide customer support</li>
                <li>Gather analysis or valuable information to improve our service</li>
            </ul>

            <h2>Security</h2>
            <p>We value your trust in providing us your personal information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>

            <h2>Changes to This Privacy Policy</h2>
            <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page.</p>

            <h2>Contact Us</h2>
            <p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us at mido.ddm2004@gmail.com</p>
        </div>
    </div>
</div>
@endsection 