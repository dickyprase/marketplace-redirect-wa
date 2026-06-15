module.exports = {
  apps: [{
    name: 'marketplace-wa',
    script: 'php',
    args: 'artisan serve --host=0.0.0.0 --port=8000',
    cwd: '/var/www/marketplace-redirect-wa',
    interpreter: 'none',
    env: {
      APP_ENV: 'local',
      APP_DEBUG: 'true'
    }
  }]
}
