<?php
// Notion CMS integration for blog posts

// Only set headers if this file is accessed directly (not included)
if (basename($_SERVER['SCRIPT_FILENAME']) === 'notion_cms.php') {
    header('Content-Type: application/json');
    header('Cache-Control: no-cache');
}

class NotionCMS {
    private $notion_token;
    private $database_id;
    
    public function __construct() {
        // Load .env file
        if (file_exists(__DIR__ . "/../.env")) {
            $lines = file(__DIR__ . "/../.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), "#") === 0) continue;
                list($name, $value) = explode("=", $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
        // Load .env file
        if (file_exists(__DIR__ . '/../.env')) {
            $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') === false) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }
        
        $this->notion_token = $_ENV['NOTION_API_TOKEN'] ?? '';
        $this->database_id = $_ENV['NOTION_DATABASE_ID'] ?? '';
    }
    
    public function fetchBlogPosts($limit = 10) {
        if (empty($this->notion_token) || empty($this->database_id)) {
            return $this->getMockData(); // Return mock data if API not configured
        }
        
        $url = "https://api.notion.com/v1/databases/{$this->database_id}/query";
        
        $data = [
            'filter' => [
                'property' => 'Status',
                'select' => [
                    'equals' => 'Published'
                ]
            ],
            'sorts' => [
                [
                    'property' => 'Published Date',
                    'direction' => 'descending'
                ]
            ],
            'page_size' => $limit
        ];
        
        $headers = [
            'Authorization: Bearer ' . $this->notion_token,
            'Notion-Version: 2022-06-28',
            'Content-Type: application/json'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return $this->getMockData();
        }
        
        $result = json_decode($response, true);
        return $this->formatBlogPosts($result['results'] ?? []);
    }
    
    public function fetchSinglePost($id) {
        if (empty($this->notion_token)) {
            return $this->getMockPost($id);
        }
        
        $url = "https://api.notion.com/v1/pages/{$id}";
        
        $headers = [
            'Authorization: Bearer ' . $this->notion_token,
            'Notion-Version: 2022-06-28'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return $this->getMockPost($id);
        }
        
        $page = json_decode($response, true);
        return $this->formatSinglePost($page);
    }
    
    private function formatBlogPosts($posts) {
        $formatted = [];
        
        foreach ($posts as $post) {
            $properties = $post['properties'];
            
            $formatted[] = [
                'id' => $post['id'],
                'title' => $this->getPropertyValue($properties['Title'] ?? []),
                'excerpt' => $this->getPropertyValue($properties['Excerpt'] ?? []),
                'category' => $this->getPropertyValue($properties['Category'] ?? []),
                'published_date' => $this->getPropertyValue($properties['Published Date'] ?? []),
                'featured_image' => $this->getPropertyValue($properties['Featured Image'] ?? []),
                'slug' => $this->generateSlug($this->getPropertyValue($properties['Title'] ?? [])),
                'url' => "single_post.php?id=" . $post['id']
            ];
        }
        
        return $formatted;
    }
    
    private function formatSinglePost($page) {
        $properties = $page['properties'];
        
        return [
            'id' => $page['id'],
            'title' => $this->getPropertyValue($properties['Title'] ?? []),
            'content' => $this->getPropertyValue($properties['Content'] ?? []),
            'excerpt' => $this->getPropertyValue($properties['Excerpt'] ?? []),
            'category' => $this->getPropertyValue($properties['Category'] ?? []),
            'published_date' => $this->getPropertyValue($properties['Published Date'] ?? []),
            'featured_image' => $this->getPropertyValue($properties['Featured Image'] ?? []),
            'author' => $this->getPropertyValue($properties['Author'] ?? []),
            'tags' => $this->getPropertyValue($properties['Tags'] ?? [])
        ];
    }
    
    private function getPropertyValue($property) {
        if (empty($property)) return '';
        
        $type = $property['type'] ?? '';
        
        switch ($type) {
            case 'title':
                if (!empty($property['title'][0]['plain_text'])) {
                    return $property['title'][0]['plain_text'];
                }
                return $property['title'][0]['text']['content'] ?? '';
            case 'rich_text':
                if (!empty($property['rich_text'][0]['plain_text'])) {
                    return $property['rich_text'][0]['plain_text'];
                }
                return $property['rich_text'][0]['text']['content'] ?? '';
            case 'select':
                return $property['select']['name'] ?? '';
            case 'date':
                return $property['date']['start'] ?? '';
            case 'files':
                if (!empty($property['files'][0])) {
                    $file = $property['files'][0];
                    if (isset($file['file']['url'])) {
                        return $file['file']['url'];
                    } elseif (isset($file['external']['url'])) {
                        return $file['external']['url'];
                    }
                }
                return '';
            case 'multi_select':
                return array_map(function($tag) {
                    return $tag['name'];
                }, $property['multi_select'] ?? []);
            default:
                return '';
        }
    }
    
    private function generateSlug($title) {
        return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
    }
    
    private function getMockData() {
        return [
            [
                'id' => 'mock-1',
                'title' => 'Mastering Instagram and Facebook Ads for Better ROI',
                'excerpt' => 'Discover proven strategies to optimize your social media advertising campaigns and maximize your return on investment.',
                'category' => 'Social Media',
                'published_date' => '2025-01-15',
                'featured_image' => './image/dummy-img-600x400.jpg',
                'slug' => 'mastering-instagram-facebook-ads-roi',
                'url' => 'single_post.php?id=mock-1'
            ],
            [
                'id' => 'mock-2',
                'title' => 'Growth Strategies for Digital Businesses in 2025',
                'excerpt' => 'Learn about the latest growth hacking techniques and digital strategies that successful businesses are using this year.',
                'category' => 'Growth Marketing',
                'published_date' => '2025-01-10',
                'featured_image' => './image/dummy-img-600x400.jpg',
                'slug' => 'growth-strategies-digital-businesses-2025',
                'url' => 'single_post.php?id=mock-2'
            ],
            [
                'id' => 'mock-3',
                'title' => 'Email Marketing Automation Best Practices',
                'excerpt' => 'Build effective email sequences that nurture leads and convert them into loyal customers with these proven automation strategies.',
                'category' => 'Email Marketing',
                'published_date' => '2025-01-05',
                'featured_image' => './image/dummy-img-600x400.jpg',
                'slug' => 'email-marketing-automation-best-practices',
                'url' => 'single_post.php?id=mock-3'
            ]
        ];
    }
    
    private function getMockPost($id) {
        $posts = [
            'mock-1' => [
                'id' => 'mock-1',
                'title' => 'Mastering Instagram and Facebook Ads for Better ROI',
                'content' => '<p>Social media advertising has become an essential component of any successful digital marketing strategy. With over 2.8 billion users on Facebook and 1 billion on Instagram, these platforms offer unprecedented opportunities to reach your target audience.</p><h3>Understanding Your Audience</h3><p>The first step to successful social media advertising is understanding who your audience is and what they want. Use Facebook\'s Audience Insights tool to gather demographic and psychographic data about your potential customers.</p><h3>Creating Compelling Ad Creative</h3><p>Your ad creative is what will make users stop scrolling and pay attention to your message. Focus on high-quality visuals, compelling copy, and clear calls-to-action.</p><h3>Optimizing for Conversions</h3><p>Set up proper tracking with Facebook Pixel and Google Analytics to measure the effectiveness of your campaigns. Test different audiences, creatives, and bidding strategies to find what works best for your business.</p>',
                'excerpt' => 'Discover proven strategies to optimize your social media advertising campaigns and maximize your return on investment.',
                'category' => 'Social Media',
                'published_date' => '2025-01-15',
                'featured_image' => './image/dummy-img-600x400.jpg',
                'author' => 'Digital Marketing Team',
                'tags' => ['Facebook Ads', 'Instagram Ads', 'ROI Optimization', 'Social Media']
            ],
            'mock-2' => [
                'id' => 'mock-2',
                'title' => 'Growth Strategies for Digital Businesses in 2025',
                'content' => '<p>Digital businesses face unique challenges and opportunities in 2025. The landscape has evolved significantly, with new technologies and changing consumer behaviors shaping how companies grow and scale.</p><h3>Leveraging AI and Automation</h3><p>Artificial intelligence and automation tools have become more accessible and powerful. Businesses that embrace these technologies can streamline operations, improve customer experiences, and scale more efficiently.</p><h3>Building Community-Driven Growth</h3><p>Community building has emerged as a powerful growth strategy. By creating engaged communities around your brand, you can foster loyalty, generate word-of-mouth marketing, and create sustainable growth loops.</p><h3>Data-Driven Decision Making</h3><p>In 2025, successful businesses are those that can effectively collect, analyze, and act on data. Implement robust analytics systems and use data to guide your strategic decisions.</p>',
                'excerpt' => 'Learn about the latest growth hacking techniques and digital strategies that successful businesses are using this year.',
                'category' => 'Growth Marketing',
                'published_date' => '2025-01-10',
                'featured_image' => './image/dummy-img-600x400.jpg',
                'author' => 'Strategy Team',
                'tags' => ['Growth Marketing', 'Digital Strategy', 'AI', 'Automation']
            ]
        ];
        
        return $posts[$id] ?? $posts['mock-1'];
    }
}

// Handle API requests - only when accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) === 'notion_cms.php') {
    $action = $_GET['action'] ?? 'posts';
    $cms = new NotionCMS();

    switch ($action) {
        case 'posts':
            $limit = intval($_GET['limit'] ?? 10);
            $posts = $cms->fetchBlogPosts($limit);
            echo json_encode(['success' => true, 'data' => $posts]);
            break;
            
        case 'post':
            $id = $_GET['id'] ?? '';
            if (empty($id)) {
                echo json_encode(['success' => false, 'error' => 'Post ID required']);
                break;
            }
            $post = $cms->fetchSinglePost($id);
            echo json_encode(['success' => true, 'data' => $post]);
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
}
?>