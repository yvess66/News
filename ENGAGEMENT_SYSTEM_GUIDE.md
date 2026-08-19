# Article Engagement System - Testing Guide

## Features Implemented ✅

### 1. Database Structure
- ✅ `articles` table with `likes`, `dislikes`, `shares` columns
- ✅ `article_reactions` table to track individual user reactions
- ✅ Unique constraint: 1 user = 1 reaction per article

### 2. Backend Features
- ✅ `ArticleReaction` model for user reactions
- ✅ `ArticleEngagementController` for handling engagement actions
- ✅ Updated `Article` model with engagement relations and helpers
- ✅ API routes for like, dislike, share actions

### 3. Frontend Features
- ✅ Engagement section in article detail page
- ✅ Like/Dislike buttons with real-time updates
- ✅ Share button with counter increment
- ✅ Visual feedback and animations
- ✅ Engagement stats display in article listings
- ✅ Mobile responsive design

### 4. User Experience
- ✅ Login required for like/dislike
- ✅ Toast notifications for feedback
- ✅ Real-time count updates
- ✅ Button state management (active/inactive)
- ✅ Animated interactions

## API Endpoints

### Authentication Required:
- `POST /articles/{article}/like` - Toggle like
- `POST /articles/{article}/dislike` - Toggle dislike

### Public:
- `POST /articles/{article}/share` - Increment share count
- `GET /articles/{article}/engagement` - Get engagement stats

## Testing Steps

### 1. Basic Functionality
```bash
# 1. Visit any article page
# 2. Check if engagement section shows
# 3. Check if stats display correctly
```

### 2. Authentication Flow
```bash
# 1. As guest: Try to like/dislike -> should prompt login
# 2. Login as user
# 3. Try like/dislike -> should work
# 4. Check real-time updates
```

### 3. User Reactions
```bash
# 1. User likes article -> like count +1, button turns active
# 2. User likes again -> like count -1, button turns inactive
# 3. User dislikes after liking -> like -1, dislike +1
# 4. Share button -> share count +1
```

### 4. Data Persistence
```bash
# 1. React to article
# 2. Refresh page
# 3. Check if reaction state persists
# 4. Check if counts are correct
```

## Database Queries to Verify

```sql
-- Check article engagement
SELECT id, title, likes, dislikes, shares FROM articles;

-- Check user reactions
SELECT ar.*, u.name, a.title 
FROM article_reactions ar 
JOIN users u ON ar.user_id = u.id 
JOIN articles a ON ar.article_id = a.id;

-- Check engagement per article
SELECT a.title, a.likes, a.dislikes, a.shares,
       COUNT(ar.id) as total_reactions
FROM articles a 
LEFT JOIN article_reactions ar ON a.id = ar.article_id 
GROUP BY a.id;
```

## Key Files Modified

### Backend:
- `app/Models/Article.php` - Added engagement relations
- `app/Models/ArticleReaction.php` - New model
- `app/Http/Controllers/ArticleEngagementController.php` - New controller
- `routes/web.php` - Added engagement routes
- `database/migrations/` - Added engagement tables

### Frontend:
- `resources/views/articles/show.blade.php` - Added engagement UI
- `resources/views/welcome.blade.php` - Added engagement stats
- `public/css/engagement.css` - New engagement styles
- `public/css/welcome.css` - Updated with mini stats

## Expected Behavior

1. **Guest Users**: Can see engagement stats, but must login to react
2. **Logged Users**: Can like/dislike, see real-time updates
3. **All Users**: Can share and see share count increment
4. **Reactions**: One user can only have one reaction (like OR dislike) per article
5. **Toggle Behavior**: Clicking same reaction removes it, clicking different reaction switches it

## Responsive Design
- ✅ Desktop: Full buttons with text and icons
- ✅ Mobile: Icon-only buttons to save space
- ✅ Toast notifications for feedback
