# Vote for Yii2

[![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-vote/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-vote)
[![Total Downloads](https://poser.pugx.org/chiliec/yii2-vote/downloads.svg)](https://packagist.org/packages/chiliec/yii2-vote)
[![Tests](https://github.com/chiliec/yii2-vote/actions/workflows/tests.yml/badge.svg)](https://github.com/chiliec/yii2-vote/actions/workflows/tests.yml)
[![License](https://poser.pugx.org/chiliec/yii2-vote/license.svg)](https://packagist.org/packages/chiliec/yii2-vote)

A flexible and easy-to-use voting extension for Yii2 that provides anonymous voting functionality for any ActiveRecord models. Support likes/dislikes with aggregate ratings and guest voting capabilities.

![How yii2-vote works](https://raw.githubusercontent.com/chiliec/yii2-vote/master/docs/showcase.gif)

## Features

- 👍 Like/dislike voting system
- 👤 Guest and authenticated user voting
- 🔄 Optional vote changing
- 📊 Aggregate rating calculations
- 🎨 Customizable widgets
- 🏆 Top-rated models widget
- 🔍 SEO-friendly with rich snippets support
- 💾 Support for MySQL, PostgreSQL, and SQLite

## Requirements

- PHP 8 or higher
- Yii2 2.0 or higher

## Installation

### Step 1: Install via Composer

```bash
composer require chiliec/yii2-vote "^4.3"
```

### Step 2: Configure Your Application

Add the following to your application configuration file (e.g., `config/main.php`):

```php
'bootstrap' => [
    'chiliec\vote\components\VoteBootstrap',
],
'modules' => [
    'vote' => [
        'class' => 'chiliec\vote\Module',

        // Display messages in popover (default: false)
        'popOverEnabled' => true,

        // Global settings for all models
        'allowGuests' => true,        // Allow guests to vote (default: true)
        'allowChangeVote' => true,    // Allow users to change their vote (default: true)

        // Register your models
        'models' => [
            // Simple registration
            \common\models\Post::class,

            // With string notation
            'backend\models\Post',

            // With custom ID
            2 => 'frontend\models\Story',

            // With model-specific settings (overrides global settings)
            3 => [
                'modelName' => \backend\models\Mail::class,
                'allowGuests' => false,      // Only authenticated users can vote
                'allowChangeVote' => false,  // Users cannot change their vote
            ],
        ],
    ],
],
```

### Step 3: Run Migrations

Apply the database migrations to create the required tables:

```bash
php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

## Usage

### Basic Vote Widget

Add the vote widget to your view:

```php
<?= \chiliec\vote\widgets\Vote::widget([
    'model' => $model,
    // Optional: show aggregate rating
    'showAggregateRating' => true,
]) ?>
```

### Top Rated Widget

Display a list of top-rated models:

```php
<?= \chiliec\vote\widgets\TopRated::widget([
    'modelName' => \common\models\Post::class,
    'title' => 'Top Rated Posts',
    'path' => 'site/view',
    'limit' => 10,
    'titleField' => 'title',
]) ?>
```

## Advanced Configuration

### Accessing Vote Data in Your Models

Once configured, your models will have access to voting data through the automatically attached behavior:

```php
$post = Post::findOne(1);
echo $post->aggregate->likes;     // Number of likes
echo $post->aggregate->dislikes;  // Number of dislikes
echo $post->aggregate->rating;    // Calculated rating
```

### Manual Behavior Attachment

If you prefer to manually attach the behavior to specific models:

```php
public function behaviors()
{
    return [
        'vote' => [
            'class' => \chiliec\vote\behaviors\VoteBehavior::class,
        ],
    ];
}
```

## Documentation

For detailed documentation, see [docs/README.md](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md):

- [Migration Guide from 2.* to 3.0](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#migration-from-2-to-30)
- [Manual Behavior Configuration](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#manually-add-behavior-in-models)
- [Sorting by Rating in Data Providers](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#sorting-by-rating-in-data-provider)
- [Overriding Views](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#overriding-views)
- [Customizing JavaScript Events](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#customizing-js-events)
- [SEO Rich Snippets](https://github.com/chiliec/yii2-vote/blob/master/docs/README.md#rich-snippet-in-search-engines)

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](https://github.com/chiliec/yii2-vote/blob/master/CONTRIBUTING.md) for details.

## Contributors

- [chiliec](https://github.com/chiliec) - Maintainer
- [loveorigami](https://github.com/loveorigami) - Ideological inspirer
- [fourclub](https://github.com/fourclub) - PK name fix in behavior
- [yurkinx](https://github.com/yurkinx) - Duplication JS render fix
- [n1k88](https://github.com/n1k88) - German translation
- [teranchristian](https://github.com/teranchristian) - Add popover to display messages
- [Skatox](https://github.com/Skatox) - PostgreSQL support

## Alternative Solutions

Looking for other voting solutions for Yii2?

- [yii2-vote by hauntd](https://github.com/hauntd/yii2-vote) - Vote widgets, like and favorite buttons
- [yii2-vote by bigdropinc](https://github.com/bigdropinc/yii2-vote) - Alternative voting implementation

## Resources

- [Programming With Yii2: Building Community With Voting, Comments, and Sharing](https://code.tutsplus.com/tutorials/programming-with-yii-building-community-with-voting-comments-and-sharing--cms-27798) by Jeff Reifman

## License

yii2-vote is released under the BSD 3-Clause License. See [LICENSE.md](https://github.com/chiliec/yii2-vote/blob/master/LICENSE.md) for details.

## Support

If you find this extension useful, please consider:
- ⭐ Starring the repository
- 🐛 Reporting issues
- 📝 Contributing improvements
- 💬 Sharing your experience

---

Made with ❤️ by the Yii2 community
