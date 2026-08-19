<!-- Recommendations Section -->
<div class="recommendations-section" id="recommendationsSection">
    <div class="section-header">
        <span class="section-label">REKOMENDASI BERITA</span>
        <div class="section-border"></div>
    </div>
    
    <div class="recommendations-grid" id="recommendationsGrid">
        <!-- Recommendations will be loaded here via JavaScript -->
        <div class="loading-recommendations">
            <p>Memuat rekomendasi artikel...</p>
        </div>
    </div>
    
    <div class="recommendations-empty" id="recommendationsEmpty" style="display: none;">
        <p>Tidak ada artikel terkait yang ditemukan.</p>
    </div>
</div>

<style>
/* Recommendations Section Styles */
.recommendations-section {
    margin-top: 40px;
    padding: 20px 0;
    border-top: 1px solid #eaeaea;
}

.recommendations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.recommendation-card {
    border: 1px solid #eaeaea;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #fff;
    text-decoration: none;
    color: inherit;
}

.recommendation-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    text-decoration: none;
    color: inherit;
}

.recommendation-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    position: relative;
}

.recommendation-content {
    padding: 15px;
}

.recommendation-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.recommendation-summary {
    font-size: 14px;
    color: #666;
    margin-bottom: 12px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
}

.recommendation-meta {
    font-size: 12px;
    color: #888;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.recommendation-category {
    background: var(--primary-color);
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}

.recommendation-date {
    font-style: italic;
}

.loading-recommendations {
    grid-column: 1 / -1;
    text-align: center;
    padding: 30px;
    color: #666;
}

.recommendations-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 30px;
    color: #888;
    font-style: italic;
}

/* Responsive Design */
@media (max-width: 768px) {
    .recommendations-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .recommendation-image {
        height: 140px;
    }
}

@media (max-width: 576px) {
    .recommendations-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get article ID from meta tag
    const metaArticleId = document.querySelector('meta[name="article-id"]');
    
    if (metaArticleId) {
        loadRecommendations(metaArticleId.getAttribute('content'));
    } else {
        hideRecommendationsSection();
    }
});

function loadRecommendations(articleId) {
    const recommendationsGrid = document.getElementById('recommendationsGrid');
    const recommendationsEmpty = document.getElementById('recommendationsEmpty');
    
    // Show loading state
    recommendationsGrid.innerHTML = '<div class="loading-recommendations"><p>Memuat rekomendasi artikel...</p></div>';
    
    // Make API call to get recommendations
    fetch(`/api/recommendations?article_id=${articleId}&limit=6`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.recommendations && data.recommendations.length > 0) {
                displayRecommendations(data.recommendations);
            } else {
                showEmptyState();
            }
        })
        .catch(error => {
            console.error('Error loading recommendations:', error);
            showEmptyState();
        });
}

function displayRecommendations(recommendations) {
    const recommendationsGrid = document.getElementById('recommendationsGrid');
    
    recommendationsGrid.innerHTML = '';
    
    recommendations.forEach(article => {
        const card = createRecommendationCard(article);
        recommendationsGrid.appendChild(card);
    });
}

function createRecommendationCard(article) {
    const card = document.createElement('a');
    card.className = 'recommendation-card';
    card.href = article.url;
    
    const imageUrl = article.featured_image || 'https://via.placeholder.com/280x160?text=No+Image';
    
    card.innerHTML = `
        <img src="${imageUrl}" alt="${article.title}" class="recommendation-image" 
             onerror="this.src='https://via.placeholder.com/280x160?text=Error+Loading+Image'">
        <div class="recommendation-content">
            <h4 class="recommendation-title">${article.title}</h4>
            <p class="recommendation-summary">${article.summary || 'Tidak ada ringkasan tersedia.'}</p>
            <div class="recommendation-meta">
                ${article.category ? `<span class="recommendation-category">${article.category}</span>` : ''}
                <span class="recommendation-date">${article.created_at}</span>
            </div>
        </div>
    `;
    
    return card;
}

function showEmptyState() {
    const recommendationsGrid = document.getElementById('recommendationsGrid');
    const recommendationsEmpty = document.getElementById('recommendationsEmpty');
    
    recommendationsGrid.style.display = 'none';
    recommendationsEmpty.style.display = 'block';
}

function hideRecommendationsSection() {
    const recommendationsSection = document.getElementById('recommendationsSection');
    if (recommendationsSection) {
        recommendationsSection.style.display = 'none';
    }
}
</script>
