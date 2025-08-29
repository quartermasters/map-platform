<?php
// Dashboard to display scraped data

$scraped_data = [];
if (file_exists('scraped_titles.json')) {
    $json_content = file_get_contents('scraped_titles.json');
    $scraped_data = json_decode($json_content, true);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data Dashboard</title>
    <style>
        body { font-family: sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #0056b3; }
        ul { list-style: none; padding: 0; }
        li { background: #e9e9e9; margin-bottom: 10px; padding: 10px; border-radius: 5px; }
        .refresh-button { display: inline-block; padding: 10px 15px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .refresh-button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Latest Scraped Titles</h1>
        <?php if (!empty($scraped_data)): ?>
            <ul>
                <?php foreach ($scraped_data as $title): ?>
                    <li><?php echo htmlspecialchars($title); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No data scraped yet. Click the button below to run the scraper.</p>
        <?php endif; ?>
        <a href="scraper.php" class="refresh-button">Refresh Data (Run Scraper)</a>
    </div>
</body>
</html>