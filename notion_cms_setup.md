# 🚀 Notion CMS Setup Guide for Marko Digital Marketing Agency

## Overview
Your website now has a dynamic blog system powered by Notion CMS! You can manage all blog content through Notion's familiar interface without touching any code.

## 📋 Prerequisites
1. A Notion account (free or paid)
2. Basic understanding of Notion databases

## 🔧 Setup Instructions

### Step 1: Create Notion Integration
1. Go to [https://www.notion.so/my-integrations](https://www.notion.so/my-integrations)
2. Click **"+ New Integration"**
3. Fill in the details:
   - **Name**: Marko Website Blog
   - **Logo**: Upload your company logo (optional)
   - **Description**: CMS integration for website blog posts
4. Select your workspace
5. Click **"Submit"**
6. **Copy the Internal Integration Token** - you'll need this later

### Step 2: Create Blog Database in Notion
1. Create a new page in Notion called "Blog Posts"
2. Add a database with these exact property names:

| Property Name | Type | Description |
|---------------|------|-------------|
| Title | Title | Blog post title |
| Status | Select | Options: "Draft", "Published", "Archived" |
| Excerpt | Rich Text | Short description for blog listing |
| Content | Rich Text | Full blog post content |
| Category | Select | Post category (e.g., "Social Media", "SEO", "PPC") |
| Published Date | Date | When to publish the post |
| Featured Image | Files & Media | Main image for the post |
| Author | Rich Text | Author name |
| Tags | Multi-select | Post tags for better organization |

### Step 3: Configure Database Permissions
1. In your Notion database, click **"Share"** (top right)
2. Click **"Invite"**
3. Search for your integration name ("Marko Website Blog")
4. Select it and give it **"Edit"** permissions
5. Click **"Invite"**

### Step 4: Get Database ID
1. In your Notion database, click **"Share"** → **"Copy link"**
2. The URL will look like: `https://www.notion.so/workspace/32charshere?v=...`
3. **Copy the 32-character string** (this is your Database ID)

### Step 5: Add Environment Variables to Replit
1. In your Replit project, go to **"Secrets"** tab (🔒 icon in sidebar)
2. Add these two secrets:

| Key | Value |
|-----|-------|
| `NOTION_API_TOKEN` | Your Integration Token from Step 1 |
| `NOTION_DATABASE_ID` | Your Database ID from Step 4 |

### Step 6: Test Your Setup
1. Add a test blog post in your Notion database:
   - **Title**: "Welcome to Our New Blog"
   - **Status**: "Published"
   - **Excerpt**: "We're excited to share our latest insights with you"
   - **Content**: "This is our first blog post using Notion CMS..."
   - **Category**: "Company News"
   - **Published Date**: Today's date
   - **Author**: "Marketing Team"

2. Visit your website at `/blog.php` to see if it loads

## 📝 How to Manage Content

### Adding New Blog Posts
1. Open your Notion "Blog Posts" database
2. Click **"+ New"** to create a new row
3. Fill in all the required fields
4. Set **Status** to **"Published"** when ready to go live
5. Your post will automatically appear on the website!

### Editing Existing Posts
1. Find the post in your Notion database
2. Click on any field to edit
3. Changes are reflected on your website immediately

### Organizing Content
- Use **Categories** to group related posts
- Add **Tags** for better searchability
- Use **Status** to control visibility:
  - "Draft" = Not visible on website
  - "Published" = Visible on website
  - "Archived" = Hidden but preserved

## 🎨 Content Guidelines

### Featured Images
- Upload images directly to the "Featured Image" field in Notion
- Recommended size: 600x400 pixels or similar ratio
- Formats: JPG, PNG, WebP

### Writing Content
- Use Notion's rich text editor with headings, lists, and formatting
- The system supports:
  - **Bold** and *italic* text
  - Headers (H1, H2, H3)
  - Bulleted and numbered lists
  - Links
  - Basic formatting

### SEO Best Practices
- Write compelling titles (50-60 characters)
- Create engaging excerpts (150-160 characters)
- Use relevant categories and tags
- Include target keywords naturally

## 🔧 Technical Details

### File Structure
- `php/notion_cms.php` - Backend API for Notion integration
- `js/blog-cms.js` - Frontend JavaScript for dynamic rendering
- `blog.php` - Dynamic blog listing page
- `single_post.php` - Dynamic individual post page

### Fallback Mode
If Notion API is not configured, the system automatically shows sample blog posts so your website continues to work.

### Caching
- Blog posts are fetched fresh from Notion on each page load
- For high-traffic sites, consider adding caching in the future

## 🆘 Troubleshooting

### Posts Not Showing Up?
1. Check if **Status** is set to "Published"
2. Verify **Published Date** is not in the future
3. Confirm Notion integration has proper permissions
4. Check Replit Secrets are correctly set

### Images Not Loading?
1. Ensure images are uploaded to Notion (not just linked)
2. Check image file formats (JPG, PNG recommended)
3. Try re-uploading the image in Notion

### Need Help?
Contact your development team with:
- Screenshot of the issue
- The blog post URL that's not working
- Any error messages you see

## 🎉 You're All Set!

Your Notion CMS is now ready to use. You can write and publish blog posts directly from Notion, and they'll automatically appear on your website. No more code editing required!

### Quick Links:
- 📝 [Your Notion Database](https://notion.so) (add your actual link here)
- 🌐 [Blog Page](./blog.php)
- 📱 [Replit Secrets](https://replit.com/@your-username/your-project-name?v=1&path=.replit) (update with your actual URL)

---
*Last updated: January 2025*