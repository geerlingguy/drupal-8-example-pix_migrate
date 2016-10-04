# Example Migration - Media Gallery Drupal 6 to 8 Upgrade

This module contains all the migration configurations I used to upgrade a Drupal 6 media gallery website to Drupal 8.

The Drupal 6 site had the following content types, taxonomies and relations:

  - Gallery (content type)
  - Image (content type)
    - Related to Gallery via node reference
    - Related to 'Names' Taxonomy
  - 'Names' Taxonomy
  - Video or Audio Clip (not migrated)

The Drupal 8 site was structured slightly differently to use the Media Entity suite of modules:

  - Gallery (node type)
    - Contains Image field that relates to image media entities
  - Image (image media entity)
    - Related to 'Names' taxonomy
  - 'Names' Taxonomy
  - Video Clip (video media entity)
  - Audio Clip (audio media entity)

I published this module for reference in this blog post, which walks through the entire migration development process and refers back to the code here: [Migrating 20,000 images, audio clips, and video clips into Drupal 8](http://www.jeffgeerling.com/blog/2016/migrating-20000-images-audio-clips-and-video-clips-drupal-8).

## License

GPLv2

## Author Information

This module was created in 2016 by [Jeff Geerling](http://www.jeffgeerling.com/), author of [Ansible for DevOps](https://www.ansiblefordevops.com/).
