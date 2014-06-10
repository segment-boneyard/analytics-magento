casper.test.begin('As a guest check that we have all necessary code on a category page', function suite(test) {

    var baseUrl = casper.cli.get('url');
    if (typeof baseUrl == 'undefined') {
        casper.test.fail('You need to specify base url ');
    }

    casper.start(baseUrl + storeConfig.firstTestCategory , function () {
        test.assertHttpStatus(200);
        this.test.pass('Category page is loaded');
        test.assertSelectorHasText('#segmentio-analytics-init',
            "window.analytics.page('Category','Cell Phones'", 'Category tracking code is present on the page');
    });

    casper.run(function() {
        this.clear();
        phantom.clearCookies();
        test.done();
    });
});