# Fegg Tool RSS

The extends class that getting information from RSS feed for [Fegg](https://github.com/genies-inc/Fegg)

## Example

```php
$rss = $this->getClass('Tool/RSS', 'http://example.com/feed');

while($row = $rss->fetch()) {
    echo $row['title'];
}

// or

$results = $rss->fetchAll();
foreach($results as $row) {
    echo $row['title'];
}
```


## Response

| key         | type     | remark                            |
|-------------|----------|-----------------------------------|
| title       | string   | Post title                        |
| pubDate     | string   | Post date                         |
| datetime    | DateTime | Post date (converted to DateTime) |
| link        | string   | Post permalink                    |
| description | string   | Post description                  |
| content     | string   | Post content                      |
| author      | string   | Post auhtor                       |
| category    | array    | Post categories                   |
| tag         | array    | Post tags (Unimplemented)         |
