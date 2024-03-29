# CiviCRM: Texas Campaing Tweaks
## com.joineryhq.txcampaigntweaks

Special CiviCRM features for texascampaign.org.

The extension is licensed under [GPL-3.0](LICENSE.txt).

This extension provides the following customizations to standard CiviCRM features:

* On any online contribution receipt, any "Groups" profile field will be omitted.
  In this way the receipt never contains a list of the donor's subscribed groups,
  even if those that the contact selected in the contribution form.
* On emailed reports (i.e. api job.mail_report) where format is 'csv' or 'pdf',
  the link to the report appearing in the email body will be replaced with a
  back-end URL.  
  Rationale: On WordPress, CiviCRM uses a front-end URL, which has these disadvantages:
  * The front-end UI is surprising to users who typically access reports via the
    back-end;
  * If the user is not logged in, a front-end URL will simply display a fatal
    permission-denied error; but on the back-end, WordPress will prompt for login.
  * To support CSS styles on 'healthyfutures' WordPress theme on Contribution
    Pages, add class 'has-no-recur-section' to div#priceset where no recurring
    options are offered on the page.

## Requirements

* CiviCRM >= 5.0

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl com.joineryhq.txcampaigntweaks@https://github.com/FIXME/com.joineryhq.txcampaigntweaks/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/com.joineryhq.txcampaigntweaks.git
cv en txcampaigntweaks
```

## Usage/Configuration

This extension has no configuration options.

## Support
![Joinery](/images/joinery-logo.png)

Joinery provides services for CiviCRM including custom extension development, training,
data migrations, and more. We aim to keep this extension in good working order, and will
do our best to respond appropriately to issues reported on its
[github issue queue](https://github.com/twomice/com.joineryhq.txcampaigntweaks/issues).
In addition, if you require urgent or highly customized improvements to this extension,
we may suggest conducting a fee-based project under our standard commercial terms.
In any case, the place to start is the
[github issue queue](https://github.com/twomice/com.joineryhq.txcampaigntweaks/issues) --
let us hear what you need and we'll be glad to help however we can.

And, if you need help with any other aspect of CiviCRM -- from hosting to custom
development to strategic consultation and more -- please contact us directly via
https://joineryhq.com

