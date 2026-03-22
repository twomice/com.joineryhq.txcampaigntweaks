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

Support for this package is handled under Joinery's ["As-Is Support" policy](https://joineryhq.com/software-support-levels#as-is-support).

Public issue queue for this package: https://github.com/JoineryHQ/com.joineryhq.txcampaigntweaks/issuesgithub.com/twomice/com.joineryhq.txcampaigntweaks/issues
