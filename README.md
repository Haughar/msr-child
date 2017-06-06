# MSR Child Theme

## Overview of project
**Problem Statement:**
How might we create a fundraising platform so that MSR Global Health can increase awareness and support for their global health initiative from MSR’s outdoor customer audience?

**Background and Context:**
MSR, a leader in providing high quality outdoor equipment, hosted a crowdfunding campaign on Indiegogo in October, 2016. MSR developed a chlorine maker device that allows anyone to treat contaminated water with very little resources. Their goal for the campaign was to deploy the chlorine maker devices to communities that need access to clean water the most. If the donation goal was met, MSR would be able to distribute enough chlorine maker devices to provide safe drinking water for 500,000 people. From this campaign, MSR found that a large amount of donations came from the outdoor enthusiasts that buys their outdoor equipment. Now, MSR wants to create a better way to connect with this audience. MSR also is in the process of creating a nonprofit division (MSR Global Health) within the company, to focus on raising funds and projects such as providing low cost water treatment solutions. MSR Global Health has decided that a great way to better connect to their outdoor customers while supporting MSR Global Health is to create a fundraising platform that will allow people to utilize their outdoor adventures to help raise money.

## MSR Child Theme List of Contents
This repo contains the files for the MSR Fundraising Platform theme. The theme is responsible for the entire fundraising platform styling, the front end user’s account and fundraiser management, and the contribute, browse, and general contribution pages.   
- ReadMe.md  is the file that contains this document.
- functions.php is the file that contains the many functions that the rest of the files in this folder utilizes.
- style.php is the file that contains all of the CSS styling for our fundraising platform. 
- landing-page.php is the file that creates the Contribute page.
- browse.php is the file that creates the Browse page.
- fundraiser-form.php is the file that creates the Create New Fundraiser page.
- gen-contribution.php is the file that creates the General Contribution page.
- user-profile.php is the file that creates each user’s profile page.
- svg-icons.php contains the SVGs that we utilize in our fundraising platform.
- full-page.php is the file that creates an empty page, but keeps the header and footer.
- my-fundraisers.php is the file that allows users to manage their own fundraisers.
- edit-fundraiser.php is the file that allows users to edit their own fundraisers.

## Summary of Major Technology Decisions
Since our team took on a project sponsored by MSR Global Health, and we wanted to create a solution that integrated into their existing Wordpress website, we were confined to using Wordpress as our platform. To add in the fundraising platform functionality, we created a plugin for the site (msr-fundraising-platform) and a child theme (msr-child) to properly display the fundraising platform.

To keep track of which users were running which fundraisers, we added the ability for front-end users to create their own accounts.

We added “Fundraiser” as a custom post type, essentially a data type, in order to keep the fundraisers separate from the normal Wordpress posts. With custom “Fundraisers”, we can code them to look and act the way that MSR Global Health would want it to. 

To process contributions made on the fundraising platform, we utilized Stripe as they offered the best non-profit pricing for each transaction made. This would ensure that the maximum amount of money is being donated towards the organization. 

## Contact Information
Ali Haugh

haughar@uw.edu

Amber Kim

amberkim@uw.edu

Nichelle Song

nsong94@uw.edu

Michael Nguyen

mtn217@uw.edu
