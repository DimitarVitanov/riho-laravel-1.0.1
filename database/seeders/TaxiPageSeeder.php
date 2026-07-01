<?php

namespace Database\Seeders;

use App\Models\TaxiPage;
use Illuminate\Database\Seeder;

class TaxiPageSeeder extends Seeder
{
    public function run(): void
    {
        TaxiPage::updateOrCreate(
            ['slug' => 'home', 'locale' => 'en'],
            [
                // Top strip
                'taxi_strip_badge' => 'FREE',
                'taxi_strip_text' => 'Global Real Estate Market Tools & AI Reports — No Registration Required',

                // Hero
                'taxi_hero_title' => 'Real Estate Taxi is your FREE ride through the global real estate market!',
                'taxi_hero_copy' => 'Real Estate Taxi helps anyone profit from real estate, even without owning property. It gives regular people a way to understand markets, compare prices, ask questions, and find practical ways to create value.',

                // Ask AI
                'taxi_ask_title' => 'Ask anything about real estate, anywhere!',
                'taxi_ask_placeholder' => 'Type your question here.',
                'taxi_ask_note' => "Real Estate Taxi AI analyzes your question and gives you a complete report.\nCreating the report can take 30–45 seconds.",

                // Why paragraphs
                'taxi_why_paragraphs' => [
                    'Everyone, whatever your profession is, must understand that they need to be involved in some part of the real estate business.',
                    'It is not important whether you have money to buy and invest in real estate or not.',
                    'Real estate is real value that always remains a wealth factor. And at the end of the day, everyone must live in some house.',
                    'Ignoring that, or believing that it is not your kind of expertise, can damage you financially in one way or another.',
                ],

                // Purpose
                'taxi_purpose_text' => 'Real Estate Taxi is here to inform regular people and give them professional tools to profit from real estate markets <strong>worldwide.</strong>',

                // Focus
                'taxi_focus_title' => 'Smart Real Estate Decisions Made Simple',
                'taxi_focus_intro' => 'Real Estate Taxi helps regular people understand real estate and find practical ways to benefit from it, even without owning property or having money to invest.',
                'taxi_focus_areas_intro' => 'We focus on four important areas that can help you make smarter real estate decisions:',
                'taxi_focus_areas' => [
                    ['number' => '01', 'title' => 'How to earn from real estate without buying property'],
                    ['number' => '02', 'title' => 'Best real estate software and AI solutions'],
                    ['number' => '03', 'title' => 'Global residential property market analysis'],
                    ['number' => '04', 'title' => 'Worldwide property prices comparison'],
                ],
                'taxi_focus_paragraphs' => [
                    'Through these four areas, we help you find useful information, understand where opportunities may exist, compare markets, use better tools, and make more informed decisions.',
                    'Our goal is to make real estate knowledge simpler and more useful for everyone. You do not need to be a real estate agent, investor, or property owner to understand how the market works and how you may benefit from it.',
                    'We provide practical guides, market research, useful tools, AI solutions, property comparisons, and real estate ideas from around the world.',
                    'Whether you want to earn by connecting buyers and sellers, find better investment locations, compare property prices, understand rental yields, or simply learn how real estate affects your financial future, Real Estate Taxi gives you a faster and clearer way to start.',
                    'Real estate is a real value that always remains important. At the end of the day, everyone needs a place to live, rent, buy, sell, build, or invest in. Understanding real estate can help you make better financial decisions in many different ways.',
                ],

                // Topic 1
                'taxi_topic1_title' => 'How to Earn From Real Estate Without Buying Property',
                'taxi_topic1_paragraphs' => [
                    'You do not need to own a villa, apartment, or a large investment portfolio to earn from real estate.',
                    'Learn practical ways regular people can create income by finding buyers, referring investors, helping owners rent properties, generating leads, creating property content, or simply connecting the right people with the right opportunity.',
                    'Real estate is not only for people who already have money to buy property. Even without owning anything, you can become useful in the process and earn from the value you create.',
                ],

                // Topic 2
                'taxi_topic2_title' => 'Best Real Estate Software and AI Solutions',
                'taxi_topic2_paragraphs' => [
                    'Discover useful real estate software, AI tools, websites, and services that can help regular people, agents, investors, owners, and property businesses work smarter.',
                    'Find tools for property research, market analysis, price comparison, rental yield calculation, lead generation, content creation, AI property reports, buyer searches, and more.',
                    'Real Estate Taxi helps you understand which tools are useful, what they do, and how they can help you find opportunities or make better real estate decisions.',
                    'You do not need to be a real estate expert. The right tools can help anyone understand the market faster and find practical ways to earn from it.',
                ],

                // Topic 3
                'taxi_topic3_title' => 'Global Residential Property Market Analysis',
                'taxi_topic3_paragraphs' => [
                    'Get access to detailed and up-to-date residential property market reports, key metrics, and useful insights from countries around the world.',
                ],
                'taxi_topic3_question' => 'Where could it make sense to look for a real estate investment?',
                'taxi_topic3_after_paragraphs' => [
                    'If you want to know where real estate may produce better rental yield, where prices may still be affordable, or where a market may become interesting in the future, this is one of the fastest useful places to check.',
                    'Compare important market data such as property prices, rental yields, income levels, affordability, price growth, mortgage rates, taxes, and market trends.',
                    'It helps regular people get a clearer first picture before spending money, travelling to a location, or speaking with real estate agents and investors.',
                ],

                // Topic 4
                'taxi_topic4_title' => 'Worldwide Property Prices Comparison',
                'taxi_topic4_question' => 'Is this city expensive, or could it still be interesting compared with other cities and countries?',
                'taxi_topic4_paragraphs' => [
                    'Use the property prices comparison tool to compare real estate prices, apartment prices, rental prices, and affordability between different locations worldwide.',
                    'This is very useful for a quick comparison between cities and countries.',
                ],
                'taxi_topic4_list_title' => 'Useful for:',
                'taxi_topic4_list_items' => [
                    'Property price comparison',
                    'Price-to-income ratio',
                    'Rental yield estimates',
                    'Affordability',
                    'City comparison',
                    'Quick first market feeling',
                ],
                'taxi_topic4_closing' => 'It helps you quickly see whether a city looks overpriced, affordable, or potentially interesting when compared with local income and possible rental returns.',

                // Footer
                'taxi_footer_description' => 'Your free ride through the global real estate market. Tools, analysis, AI reports, and practical guides for regular people worldwide.',
                'taxi_footer_subscribe_title' => 'Stay Updated',
                'taxi_footer_subscribe_text' => 'Get our daily real estate market newsletter with practical tips, tools, and AI reports.',

                // Meta
                'taxi_meta_title' => 'Real Estate Taxi — Free Global Real Estate Market Tools',
                'taxi_meta_description' => 'Real Estate Taxi helps anyone profit from real estate, even without owning property.',
            ]
        );
    }
}
