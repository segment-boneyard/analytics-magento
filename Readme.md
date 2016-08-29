
# Analytics for Magento

> **NOTE**: Official support from Segment for this plugin is **deprecated**. We are currently looking for community maintainers. The current version of this plugin should be free of bugs but any existing and future development will be paused for the time being. We recommend exploring and using third party alternatives. If you have any questions or have interest in stewarding the Segment Magento plugin, please send us an email at friends@segment.com!

**Analytics for Magento** is a Magento extension for [Segment](https://segment.com) that lets you send data to any analytics tool you want without writing any code yourself!

- [Installing](#installing)
- [Support](#support)
- [Developing](#developing)
- [Deploying](#deploying)


## Installing

To get up and running, check out our documentation at [https://segment.com/docs/plugins/magento](https://segment.com/docs/plugins/magento). Installation takes less than five minutes!


## Support

If you run into issues, be sure to check out the [documentation](https://segment.com/docs/plugins/magento), and you can always reach out to our [support team](https://segment.com/support) for help!


## Developing

_Note: this section only applies if you are interested in contributing improvements to our Magento extension's source code. You don't need to read this to use the extension in your own store._

TODO: We don't yet have detailed instructions for installing a local development version of Magento.


## Deploying

_Note: this section only applies to Segment teammates, and details how we deploy new versions of the extension to the Magento Connect marketplace for end users of the extension to download._

Before deploying, make sure to pull in the latest changes from the remote repository:

    $ git pull origin master

The first step after making your changes to the extension source is to tag a new release of the extension. To do that, first bump the version number in [`app/etc/modules/Segment_Analytics.xml`](app/code/community/Segment/Analytics/etc/config.xml).

Then, update [`History.md`](History.md) with a summary of your changes.

Once you've bumped the version and updated the changelog, you need to rebuild the distribution files in [`dist/`](dist/) by running the build script:

    $ bin/build

Then commit those changes, and tag the release (where `X.X.X` is the number of the newest version):

    $ git commit -am 'X.X.X'
    $ git tag 'X.X.X'
    $ git push origin master
    $ git push origin X.X.X

Once you've done that, you'll need to log into our [Magento Connect]() account, and navigate to **Your Account > Developers > Manage Extensions** and click the **Edit** button on the **Analytics for Magento** extension. That will take you to the extension editing page. From there, click the **Versions** tab and then the **Add new version** button.

Fill out the form for adding a new version, supplying the `X.X.X` version number as the **Release Notes Title** as well, and the list from [`History.md`](History.md) as the **Release Notes**. For the **Versions** check `1.7` and above. Then click the **Continue to upload** button.

On the upload form page click **Choose File** and find the `analytics-magento-X.X.X` file on your computer from the [`dist/`](dist) directory, then hit **Upload and save**. 

And you're finally done!


## Resources

- [Analytics for Magento Listing](http://www.magentocommerce.com/magento-connect/extension-171.html) - Our extension's listing on Magento Connect.
- [Magento Tar to Connect](http://alanstorm.com/magento_connect_from_tar) - The script we use to build Magento Connect compatible files.
- [Magento Extension Design Guidelines](http://info.magento.com/rs/magentocommerce/images/0448_Connect_DevStyleguide_v6.pdf?mkt_tok=3RkMMJWWfF9wsRokvK7BZKXonjHpfsX94%2B0oWKSg38431UFwdcjKPmjr1YEGTcZ0dvycMRAVFZl5nQFZHeWbaI9D9fhQDlOxXQ%3D%3D) - Guidelines for extensions to adhere to. 


## License

Copyright &copy; 2014 Segment &lt;friends@segment.com&gt;

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the 'Software'), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
