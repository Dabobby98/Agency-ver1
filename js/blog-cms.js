/**
 * Dynamic Blog CMS functionality
 * Handles fetching and rendering blog posts from Notion CMS
 */

class BlogCMS {
    constructor() {
        this.apiBase = './php/notion_cms.php';
        this.init();
    }
    
    init() {
        // Check if we're on blog page or single post page
        if (window.location.pathname.includes('blog.html') || window.location.pathname.includes('blog.php')) {
            this.loadBlogPosts();
        } else if (window.location.pathname.includes('single_post.html') || window.location.pathname.includes('single_post.php')) {
            this.loadSinglePost();
        }
    }
    
    async loadBlogPosts() {
        try {
            const response = await fetch(`${this.apiBase}?action=posts&limit=6`);
            const result = await response.json();
            
            if (result.success && result.data) {
                this.renderBlogPosts(result.data);
            } else {
                console.error('Failed to load blog posts:', result.error);
            }
        } catch (error) {
            console.error('Error fetching blog posts:', error);
        }
    }
    
    async loadSinglePost() {
        const urlParams = new URLSearchParams(window.location.search);
        const postId = urlParams.get('id');
        
        if (!postId) {
            this.showPostError('No post ID provided');
            return;
        }
        
        try {
            const response = await fetch(`${this.apiBase}?action=post&id=${postId}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                this.renderSinglePost(result.data);
            } else {
                this.showPostError('Post not found');
            }
        } catch (error) {
            console.error('Error fetching post:', error);
            this.showPostError('Failed to load post');
        }
    }
    
    renderBlogPosts(posts) {
        const blogContainer = document.querySelector('.row.row-cols-md-2');
        if (!blogContainer) return;
        
        blogContainer.innerHTML = '';
        
        posts.forEach(post => {
            const blogCard = this.createBlogCard(post);
            blogContainer.appendChild(blogCard);
        });
    }
    
    createBlogCard(post) {
        const col = document.createElement('div');
        col.className = 'col';
        
        const formattedDate = this.formatDate(post.published_date);
        
        col.innerHTML = `
            <div class="card card-blog animate-box animated animate__animated" data-animate="animate__fadeInUp" onclick="window.location.href='${post.url}'">
                <div class="blog-image">
                    <img src="${post.featured_image || './image/dummy-img-600x400.jpg'}" alt="${post.title}">
                </div>
                <div class="card-body">
                    <div class="d-flex flex-row gspace-2">
                        <div class="d-flex flex-row gspace-1 align-items-center">
                            <i class="fa-solid fa-calendar accent-color"></i>
                            <span class="meta-data">${formattedDate}</span>
                        </div>
                        <div class="d-flex flex-row gspace-1 align-items-center">
                            <i class="fa-solid fa-folder accent-color"></i>
                            <span class="meta-data">${post.category || 'General'}</span>
                        </div>
                    </div>
                    <a href="${post.url}" class="blog-link">${post.title}</a>
                    <p>${post.excerpt || ''}</p>
                    <a href="${post.url}" class="read-more">Read More</a>
                </div>
            </div>
        `;
        
        return col;
    }
    
    renderSinglePost(post) {
        // Update page title
        const titleElement = document.querySelector('.section-banner .title-heading');
        if (titleElement) {
            titleElement.textContent = post.title;
        }
        
        // Update breadcrumb
        const breadcrumbCurrent = document.querySelector('.breadcrumb .current-page');
        if (breadcrumbCurrent) {
            breadcrumbCurrent.textContent = post.title;
        }
        
        // Update featured image
        const postImage = document.querySelector('.post-image img');
        if (postImage && post.featured_image) {
            postImage.src = post.featured_image;
            postImage.alt = post.title;
        }
        
        // Update page title and meta description
        document.title = `${post.title} - Nexon Digital Marketing Agency`;
        
        const metaDescription = document.querySelector('meta[name="description"]');
        if (metaDescription && post.excerpt) {
            metaDescription.setAttribute('content', post.excerpt);
        }
        
        // Update post content
        const postContentContainer = document.querySelector('.col.col-xl-8.order-1.order-xl-2 .d-flex.flex-column.gspace-2');
        if (postContentContainer && post.content) {
            // Clear existing content after featured image
            const existingElements = postContentContainer.children;
            for (let i = existingElements.length - 1; i >= 2; i--) {
                existingElements[i].remove();
            }
            
            // Add post metadata
            const metaDiv = document.createElement('div');
            metaDiv.className = 'd-flex flex-row gspace-3 align-items-center mb-3';
            const formattedDate = this.formatDate(post.published_date);
            metaDiv.innerHTML = `
                <div class="d-flex flex-row gspace-1 align-items-center">
                    <i class="fa-solid fa-calendar accent-color"></i>
                    <span class="meta-data">${formattedDate}</span>
                </div>
                <div class="d-flex flex-row gspace-1 align-items-center">
                    <i class="fa-solid fa-folder accent-color"></i>
                    <span class="meta-data">${post.category || 'General'}</span>
                </div>
                ${post.author ? `
                <div class="d-flex flex-row gspace-1 align-items-center">
                    <i class="fa-solid fa-user accent-color"></i>
                    <span class="meta-data">${this.sanitizeHtml(post.author)}</span>
                </div>
                ` : ''}
            `;
            
            // Add content with sanitization
            const contentDiv = document.createElement('div');
            contentDiv.className = 'post-content';
            contentDiv.innerHTML = this.sanitizeHtml(post.content);
            
            postContentContainer.appendChild(metaDiv);
            postContentContainer.appendChild(contentDiv);
            
            // Add tags if available
            if (post.tags && post.tags.length > 0) {
                const tagsDiv = document.createElement('div');
                tagsDiv.className = 'post-tags mt-4';
                tagsDiv.innerHTML = `
                    <h5>Tags:</h5>
                    <div class="d-flex flex-wrap gap-2">
                        ${post.tags.map(tag => `<span class="badge bg-secondary">${tag}</span>`).join('')}
                    </div>
                `;
                postContentContainer.appendChild(tagsDiv);
            }
        }
        
        // Load recent posts for sidebar
        this.loadRecentPosts();
    }
    
    async loadRecentPosts() {
        try {
            const response = await fetch(`${this.apiBase}?action=posts&limit=3`);
            const result = await response.json();
            
            if (result.success && result.data) {
                this.renderRecentPosts(result.data);
            }
        } catch (error) {
            console.error('Error loading recent posts:', error);
        }
    }
    
    renderRecentPosts(posts) {
        const recentPostsContainer = document.querySelector('.card.recent-post');
        if (!recentPostsContainer) return;
        
        // Clear existing recent posts (keep the title)
        const existingPosts = recentPostsContainer.querySelectorAll('.d-flex.flex-row.w-100.gspace-1');
        existingPosts.forEach(post => post.remove());
        
        posts.slice(0, 2).forEach(post => {
            const recentPostDiv = document.createElement('div');
            recentPostDiv.className = 'd-flex flex-row w-100 gspace-1';
            const formattedDate = this.formatDate(post.published_date);
            
            recentPostDiv.innerHTML = `
                <div class="image-container">
                    <img src="${post.featured_image || './image/dummy-img-600x400.jpg'}" alt="${post.title}" class="img-fluid">
                </div>
                <div class="d-grid">
                    <div class="d-flex flex-row gspace-1 align-items-center">
                        <i class="fa-solid fa-calendar accent-color"></i>
                        <span class="meta-data-post">${formattedDate}</span>
                    </div>
                    <a href="${post.url}" class="blog-link-post">${post.title}</a>
                </div>
            `;
            
            recentPostsContainer.appendChild(recentPostDiv);
        });
    }
    
    formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        return date.toLocaleDateString('en-US', options);
    }
    
    sanitizeHtml(html) {
        if (!html) return '';
        
        // Create a temporary element to sanitize HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        
        // Remove potentially dangerous elements
        const dangerousElements = tempDiv.querySelectorAll('script, iframe, object, embed, form, input');
        dangerousElements.forEach(el => el.remove());
        
        // Remove dangerous attributes
        const allElements = tempDiv.querySelectorAll('*');
        allElements.forEach(el => {
            // Keep only safe attributes
            const allowedAttributes = ['href', 'src', 'alt', 'title', 'class', 'id'];
            const attributes = Array.from(el.attributes);
            attributes.forEach(attr => {
                if (!allowedAttributes.includes(attr.name.toLowerCase()) || 
                    attr.value.toLowerCase().includes('javascript:')) {
                    el.removeAttribute(attr.name);
                }
            });
        });
        
        return tempDiv.innerHTML;
    }
    
    showPostError(message) {
        // Update page title
        const titleElement = document.querySelector('.section-banner .title-heading');
        if (titleElement) {
            titleElement.textContent = 'Post Not Found';
        }
        
        // Update breadcrumb
        const breadcrumbCurrent = document.querySelector('.breadcrumb .current-page');
        if (breadcrumbCurrent) {
            breadcrumbCurrent.textContent = 'Error';
        }
        
        // Show error message
        const postContentContainer = document.querySelector('.col.col-xl-8.order-1.order-xl-2 .d-flex.flex-column.gspace-2');
        if (postContentContainer) {
            postContentContainer.innerHTML = `
                <div class="text-center p-5">
                    <i class="fa-solid fa-exclamation-triangle fa-3x accent-color mb-3"></i>
                    <h3>Oops! ${message}</h3>
                    <p>The blog post you're looking for doesn't exist or has been removed.</p>
                    <a href="./blog.php" class="btn btn-accent mt-3">
                        <span>Back to Blog</span>
                        <i class="fa-solid fa-arrow-left ms-2"></i>
                    </a>
                </div>
            `;
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BlogCMS();
});