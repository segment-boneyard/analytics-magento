casper.test.begin('Check that a necessary init code is present', function suite(test) {

    var baseUrl = casper.cli.get('url');
    if (typeof baseUrl == 'undefined') {
        casper.test.fail('You need to specify base url ');
    }

    casper.start(baseUrl, function () {
        test.assertHttpStatus(200);
        this.test.pass('Home page is loaded');
        test.assertSelectorHasText('#segmentio-analytics-init',
            'window.analytics||(window.analytics=[]),window.analytics.methods');
        test.assertSelectorHasText('#segmentio-analytics-init', 'window.analytics.load("');
        test.assertSelectorDoesntHaveText('#segmentio-analytics-init',
            'window.analytics.load("")', 'Analytics is being loaded with empty key');
    });

    casper.run(function() {
        this.clear();
        phantom.clearCookies();
        test.done();
    });
});