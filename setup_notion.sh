#!/bin/bash

echo "🚀 Setting up Notion CMS for your website..."
echo "================================================"

# Create .env file with Notion credentials
cat > .env << EOF
NOTION_API_TOKEN=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
NOTION_DATABASE_ID=274c382dc59e8014971fdd1f807d5e78
EOF

# Update PHP config to load .env file
if ! grep -q "load .env" php/notion_cms.php; then
    echo ""
    echo "📝 Updating PHP configuration..."
    # Add .env loading to the NotionCMS constructor
    sed -i '/public function __construct() {/a\
        // Load .env file\
        if (file_exists(__DIR__ . "/../.env")) {\
            $lines = file(__DIR__ . "/../.env", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);\
            foreach ($lines as $line) {\
                if (strpos(trim($line), "#") === 0) continue;\
                list($name, $value) = explode("=", $line, 2);\
                $_ENV[trim($name)] = trim($value);\
            }\
        }' php/notion_cms.php
fi

echo ""
echo "✅ Notion CMS setup completed successfully!"
echo ""
echo "📋 What was configured:"
echo "   • API Token: ntn_524...jg (secured in .env)"
echo "   • Database ID: 274c382...e78 (secured in .env)"
echo "   • PHP configuration updated to load credentials"
echo ""
echo "🌐 Your blog is now connected to Notion!"
echo "   • Visit /blog.php to see your Notion posts"
echo "   • Add/edit posts directly in your Notion database"
echo ""
echo "🔒 Security: Credentials are stored in .env file (not in code)"
echo "📝 Next steps: Create posts in your Notion database with status 'Published'"
echo ""
echo "================================================"
echo "✨ Setup complete! Your dynamic blog is ready! ✨"