DELETE FROM `#__content_types` WHERE `type_alias` IN ('com_xpert_testimonials.testimonial', 'com_xpert_testimonials.category');

DROP TABLE IF EXISTS `#__xpert_testimonials`;
