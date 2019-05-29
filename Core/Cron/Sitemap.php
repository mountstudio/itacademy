<?php
    namespace Core\Cron;

    // Setup the autoloading
    require_once __DIR__.'/../../vendor/autoload.php';

    // Setup Propel
    require_once __DIR__.'/../../generated-conf/config.php';

    // Init Monolog Logger
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    // Init Propel
    use Propel\Runtime\Propel;
    use Propel\Runtime\ActiveQuery\Criteria;
    use Propel\Runtime\ActiveQuery\ModelCriteria;

    use App\Config;
    use Core\SitemapGenerator;

    // Init Models
    use \Models\PlaceQuery;
    use \Models\StaticPageQuery;
    use \Models\PlaceCategoryQuery;
    use \Models\ConfigQuery;
    use \Models\TagQuery;



    $time = explode(" ",microtime());
    $time = $time[1];

    $siteUrl = Config::SITE_URL;

    // create object
    $sitemap = new SitemapGenerator($siteUrl, __DIR__.'/../../public/');
    // will create also compressed (gzipped) sitemap
    $sitemap->createGZipFile = true;
    // determine how many urls should be put into one file
    $sitemap->maxURLsPerSitemap = 10000;
    // sitemap file name
    $sitemap->sitemapFileName = "sitemap.xml";
    // sitemap index file name
    $sitemap->sitemapIndexFileName = "sitemap-index.xml";
    // robots file name
    $sitemap->robotsFileName = "robots.txt";
    $urls = array(
        array($siteUrl, date('c'), 'daily', '1')
    );
    // add many URLs at one time
    $sitemap->addUrls($urls);
    // add urls one by one


    $tags = TagQuery::create()->filterByCurrentPlaceIdTag(null)->find();
    foreach ($tags as $tag) {
        $sitemap->addUrl($siteUrl . 'restaurants/' . $tag->getAltName(), date('c', $tag->getUpdatedAt()->getTimestamp()),  'daily',    '0.8');
    }

    $places = PlaceQuery::create()->filterByActive(true)->find();

    $productCriteria = new Criteria();
    $productCriteria->add(\Models\Map\ProductTableMap::COL_IS_ACTIVE, true);

    foreach ($places as $place) {
        $sitemap->addUrl($siteUrl . $place->getAltName() . '-menu', date('c', $place->getUpdatedAt()->getTimestamp()),  'weekly',    '0.8');
        $sitemap->addUrl($siteUrl . $place->getAltName() . '-info', date('c', $place->getUpdatedAt()->getTimestamp()),  'weekly',    '0.6');
        $sitemap->addUrl($siteUrl . $place->getAltName() . '-otzyvy', date('c', $place->getUpdatedAt()->getTimestamp()),  'weekly',    '0.8');
        $productTags = TagQuery::create()->filterByCurrentPlaceIdTag($place->getId())->_or()->filterByCurrentPlaceIdTag(null)->useCurrentTagTagProductQuery()->useCurrentProductQuery()->filterByCurrentPlace($place)->endUse()->endUse()->groupById()->find();
        foreach ($productTags as $productTag) {
            $sitemap->addUrl($siteUrl . $place->getAltName() . '-menu/' . $productTag->getAltName(), date('c', $productTag->getUpdatedAt()->getTimestamp()),  'weekly',    '0.8');
        }

        $products = $place->getCurrentPlaceProducts($productCriteria);
        foreach ($products as $product) {
            $sitemap->addUrl($siteUrl . $place->getAltName() . '-menu/product/' . $product->getId() . '-' . $product->getAltName(), date('c', $product->getUpdatedAt()->getTimestamp()),  'weekly',    '0.7');
        }
    }

    $staticPages = StaticPageQuery::create()->filterByAvailable(true)->find();

    foreach ($staticPages as $staticPage) {
        $sitemap->addUrl($siteUrl . $staticPage->getAltUrl() . '.html', date('c', $staticPage->getUpdatedAt()->getTimestamp()),  'weekly',    '0.8');
    }

    try {
        // create sitemap
        $sitemap->createSitemap();
        // write sitemap as file
        $sitemap->writeSitemap();
        // update robots.txt file
        $sitemap->updateRobots();
        // submit sitemaps to search engines
        $result = $sitemap->submitSitemap();
        // shows each search engine submitting status
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
    echo "Memory peak usage: ".number_format(memory_get_peak_usage()/(1024*1024),2)."MB";
    $time2 = explode(" ",microtime());
    $time2 = $time2[1];
    echo "<br>Execution time: ".number_format($time2-$time)."s";
?>
