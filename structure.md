wp-content/plugins/zorgfinder-reviews/
│
├── zorgfinder-reviews.php             # Main plugin entry file
├── composer.json                      # PSR-4 autoloader
├── vendor/                            # Composer dependencies (gitignored)
│
├── src/
│   ├── API/
│   │   └── ReviewsController.php      # REST endpoints (GET/POST)
│   │
│   ├── Database/
│   │   ├── Migrations/
│   │   │   └── CreateReviewsTable.php # Migration for zf_reviews
│   │   └── Models/
│   │       └── Review.php             # Review model
│   │
│   ├── Services/
│   │   └── ReviewService.php          # Business logic (average rating calc)
│   │
│   ├── Blocks/
│   │   └── ReviewBlock.php            # Gutenberg review block
│   │
│   ├── Traits/
│   │   └── SingletonTrait.php
│   │
│   └── Core.php                       # Plugin bootstrapper
│
├── bootstrap/
│   ├── Activator.php
│   ├── Deactivator.php
│   ├── Uninstaller.php
│   └── setup.php
│
├── config/
│   ├── constants.php
│   ├── routes.php
│   └── services.php
│
└── readme.md
