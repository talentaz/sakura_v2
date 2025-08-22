<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Support\Facades\File;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample news data
        $newsData = [
            [
                'title' => 'Meet up with Company lawyer and the team',
                'description' => '<p>Sakura Motors Tanzania shareholders Mr Nobuki de Souza and Mr Masaki Kawaguchi, with Sakura Motors Tanzania team meet the Company lawyer Mr Ridhiwani.</p><p>This meeting was focused on discussing important legal matters and ensuring compliance with all regulatory requirements for our operations in Tanzania.</p><p>The team discussed various aspects of business operations, legal compliance, and future expansion plans. This collaboration ensures that Sakura Motors continues to operate within the legal framework while providing excellent service to our customers.</p>',
                'date' => '2025-08-20',
                'user' => 'Admin'
            ],
            [
                'title' => 'New Vehicle Arrivals at Sakura Motors',
                'description' => '<p>We are excited to announce the arrival of new vehicles at our showroom. Our latest shipment includes a variety of high-quality used cars from Japan.</p><p>These vehicles have been carefully inspected and meet our strict quality standards. Each vehicle comes with detailed inspection reports and maintenance history.</p><p>Visit our showroom to explore the latest arrivals and find your perfect vehicle. Our experienced sales team is ready to assist you in finding the right car for your needs.</p>',
                'date' => '2025-08-18',
                'user' => 'Sales Team'
            ],
            [
                'title' => 'Sakura Motors Expansion Plans',
                'description' => '<p>Sakura Motors is pleased to announce our expansion plans for 2025. We are planning to open new branches in key locations across Tanzania.</p><p>This expansion will bring our services closer to our customers and provide better accessibility to quality Japanese used vehicles.</p><p>The new branches will feature modern facilities, experienced staff, and comprehensive after-sales services. Stay tuned for more updates on our expansion timeline.</p>',
                'date' => '2025-08-15',
                'user' => 'Management'
            ],
            [
                'title' => 'Customer Service Excellence Award',
                'description' => '<p>Sakura Motors has been recognized for excellence in customer service. This award reflects our commitment to providing outstanding service to all our customers.</p><p>Our team works tirelessly to ensure customer satisfaction at every step of the vehicle purchase process. From initial inquiry to after-sales support, we strive for excellence.</p><p>Thank you to all our customers for their trust and support. This recognition motivates us to continue improving our services.</p>',
                'date' => '2025-08-12',
                'user' => 'Customer Service'
            ],
            [
                'title' => 'Partnership with Local Financial Institutions',
                'description' => '<p>Sakura Motors has established partnerships with leading financial institutions to provide flexible financing options for our customers.</p><p>These partnerships enable us to offer competitive loan rates and flexible payment terms, making vehicle ownership more accessible.</p><p>Our finance team works closely with these institutions to ensure quick approval processes and transparent terms for all financing applications.</p>',
                'date' => '2025-08-10',
                'user' => 'Finance Team'
            ]
        ];

        foreach ($newsData as $index => $data) {
            // Create news entry
            $news = News::create($data);
            
            // Create news directory
            $newsDir = public_path('uploads/news/' . $news->id);
            if (!File::exists($newsDir)) {
                File::makeDirectory($newsDir, 0755, true);
            }
            
            // Copy placeholder image
            $placeholderPath = public_path('assets/frontend/images/news-placeholder.svg');
            $imageName = 'news-' . $news->id . '.jpg';
            $imagePath = $newsDir . '/' . $imageName;
            
            if (File::exists($placeholderPath)) {
                File::copy($placeholderPath, $imagePath);
                
                // Create news image entry
                NewsImage::create([
                    'news_id' => $news->id,
                    'image' => $imageName
                ]);
            }
        }
    }
}
