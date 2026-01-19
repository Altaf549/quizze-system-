<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Content::create([
            'page_key' => 'about-us',
            'title' => 'About Us',
            'content' => '<h1>About Quiz System</h1><p>Welcome to Quiz System, a comprehensive platform designed to make learning and assessment engaging and effective.</p><h2>Our Mission</h2><p>We are dedicated to providing educators and students with powerful tools to create, manage, and participate in quizzes that enhance the learning experience.</p><h2>What We Offer</h2><ul><li>Intuitive quiz creation interface</li><li>Comprehensive question management</li><li>Real-time result tracking</li><li>Secure and reliable platform</li></ul><h2>Why Choose Us?</h2><p>Our platform is built with educators in mind, offering features that streamline the assessment process while providing valuable insights into student performance.</p>'
        ]);

        Content::create([
            'page_key' => 'privacy-policy',
            'title' => 'Privacy Policy',
            'content' => '<h1>Privacy Policy</h1><p>Last updated: ' . date('Y-m-d') . '</p><h2>Introduction</h2><p>This Privacy Policy describes how Quiz System collects, uses, and protects your information when you use our services.</p><h2>Information We Collect</h2><p>We collect information you provide directly to us, such as when you create an account, take a quiz, or contact us for support.</p><h2>How We Use Your Information</h2><p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p><h2>Data Security</h2><p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p><h2>Contact Us</h2><p>If you have any questions about this Privacy Policy, please contact us.</p>'
        ]);

        Content::create([
            'page_key' => 'terms-conditions',
            'title' => 'Terms & Conditions',
            'content' => '<h1>Terms & Conditions</h1><p>Last updated: ' . date('Y-m-d') . '</p><h2>Acceptance of Terms</h2><p>By accessing and using Quiz System, you accept and agree to be bound by the terms and provision of this agreement.</p><h2>Use License</h2><p>Permission is granted to temporarily download one copy of the materials on Quiz System for personal, non-commercial transitory viewing only.</p><h2>Disclaimer</h2><p>The materials on Quiz System are provided on an \'as is\' basis. Quiz System makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p><h2>Limitations</h2><p>In no event shall Quiz System or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Quiz System.</p><h2>Termination</h2><p>We may terminate or suspend your account and bar access to the service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation.</p>'
        ]);
    }
}
