# JiraCal

### Goal
  Display Jira tickets in a calendar view for easier browser. (Uses issue create date)

### Installation
 1. Install via Composer
	 2. ```composer require josevh/jiracal```
 3. Set environment variables
	 4. ```JIRACAL_PATH=```
		 5. Path desired for calendar
		 6. app/PATH
	 4. ```JIRACAL_META_TITLE=```
	 5. ```JIRACAL_META_AUTHOR=```
	 6. ```JIRACAL_META_DESCRIPTION=```
	 7. ```JIRACAL_URL=```
		 8. JIRA url
	 8. ```JIRACAL_GUEST_USERNAME=```
		 9. JIRA guest login
	 9. ```JIRACAL_GUEST_PASSWORD=```
		 10. JIRA guest login
 4. Register service provider
	 5. add to ```config/app.php``` in ```providers``` array:
	 6. ```Josevh\JiraCal\JiraCalServiceProvider::class,```
 7. Publish views and configs
	 8. ```php artisan vendor:publish --provider="Josevh\JiraCal\JiraCalServiceProvider"```
 9. Done
	 10. View at http://app/PATH where PATH is configured in your ```.env``` from step 3


![screenshot](screenshot.png)
