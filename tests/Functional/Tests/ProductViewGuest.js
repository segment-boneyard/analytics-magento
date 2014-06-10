casper.test.begin('As a guest check that we have all necessary code on a product page', function suite(test) {

    var baseUrl = casper.cli.get('url');
    if (typeof baseUrl == 'undefined') {
        casper.test.fail('You need to specify base url ');
    }
    
    /* Go to the product page and check the code  */
    casper.start(baseUrl + storeConfig.firstTestProductUrl , function () {
        test.assertHttpStatus(200);
        this.test.pass('Product page is loaded');
        test.assertSelectorHasText('#segmentio-analytics-product', "analytics.track");
        test.assertSelectorHasText('#segmentio-analytics-product', '\"sku\":\"n2610\"');
        test.assertSelectorHasText('#segmentio-analytics-product', '\"name\":\"Nokia 2610 Phone\"');
        test.assertSelectorHasText('#segmentio-analytics-product', '\"price\":149.99');
        test.assertSelectorHasText('#segmentio-analytics-product', '\"category\":\"Cell Phones\"');
    });

    casper.run(function() {
        this.clear();
        phantom.clearCookies();
        test.done();
    });
});