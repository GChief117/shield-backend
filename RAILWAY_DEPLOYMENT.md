# Railway Deployment Guide

Deploy the Shield Cybersecurity Dashboard backend to Railway.

## Prerequisites

- GitHub account
- Railway account (sign up at [railway.app](https://railway.app))

## Step 1: Push Backend to GitHub

```bash
cd backend
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/shield-backend.git
git push -u origin main
```

## Step 2: Create Railway Project

1. Go to [railway.app](https://railway.app) and sign in with GitHub
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Choose your `shield-backend` repository
5. Railway will auto-detect Laravel and start deploying

## Step 3: Add PostgreSQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"PostgreSQL"**
3. Railway automatically links it to your app

## Step 4: Set Environment Variables

In Railway dashboard, go to your app → **Variables** tab and add:

```
APP_NAME=Shield
APP_ENV=production
APP_KEY=base64:GENERATE_THIS_KEY
APP_DEBUG=false
APP_URL=https://YOUR_APP.railway.app

DB_CONNECTION=pgsql

SANCTUM_STATEFUL_DOMAINS=YOUR_FRONTEND_DOMAIN.github.io,localhost:5173
CORS_ALLOWED_ORIGINS=https://YOUR_FRONTEND_DOMAIN.github.io,http://localhost:5173
FRONTEND_URL=https://YOUR_FRONTEND_DOMAIN.github.io
```

### Generate APP_KEY

Run locally:
```bash
php artisan key:generate --show
```
Copy the output and paste it as `APP_KEY` in Railway.

## Step 5: Deploy

Railway auto-deploys on every push to `main`. To manually redeploy:
1. Go to **Deployments** tab
2. Click **"Redeploy"**

## Step 6: Run Migrations & Seed

In Railway dashboard:
1. Go to your app → **Settings** tab
2. Scroll to **"Run Command"**
3. Run: `php artisan migrate --seed`

Or use Railway CLI:
```bash
railway run php artisan migrate --seed
```

## Step 7: Get Your Backend URL

After deployment, Railway gives you a URL like:
```
https://shield-backend-production.up.railway.app
```

Use this URL in your frontend's API configuration.

## Verify Deployment

Visit your Railway URL:
```
https://YOUR_APP.railway.app
```

Should return:
```json
{
  "name": "Shield Cybersecurity Dashboard API",
  "version": "1.0.0",
  "status": "running"
}
```

## Troubleshooting

### Build Fails
- Check Railway logs for errors
- Ensure `composer.json` is valid
- Verify PHP version compatibility

### Database Connection Errors
- Confirm PostgreSQL is added to project
- Check `DATABASE_URL` is automatically set by Railway
- Verify `DB_CONNECTION=pgsql` in variables

### CORS Errors
- Add your frontend domain to `CORS_ALLOWED_ORIGINS`
- Include both `http://` and `https://` versions

### 500 Errors
- Set `APP_DEBUG=true` temporarily to see errors
- Check Railway logs
- Ensure `APP_KEY` is set

## Costs

Railway free tier includes:
- $5 free credit/month
- Enough for small portfolio projects
- Sleeps after inactivity (wakes on request)

## Updating

Just push to GitHub:
```bash
git add .
git commit -m "Update"
git push
```

Railway auto-deploys on every push.
