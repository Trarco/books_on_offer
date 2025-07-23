# 📦 Changelog

## [1.1.0] – 2025-07-23

### Added
- New field `Book URL` for each book entry, allowing users to link to external pages (e.g., e-commerce).
- Support for rendering clickable book covers and a call-to-action button.
- Template logic updated to handle books with or without URLs gracefully.
- Button styling added for disabled state when URL is not available.

### Fixed
- Filemanager draft area now properly initialized for each repeated book image, ensuring images are visible after plugin update or cache reset.
- HTML template corrected to avoid invalid nested `<a>` tags.

## [1.0.0] – 2025-07-11

### Added
- Initial release of the block `books_on_offer`.
- Block title and subtitle configuration.
- Repeatable book entries with image, title, and author.
- File upload using Moodle File API.
- Carousel with:
  - Arrows on desktop
  - Swipe and dot navigation on mobile/tablet
- Responsive layout: 4 books on desktop, 2 on tablet, 1 on mobile.
- JavaScript and CSS separated and optimized.
- Restriction: only one block instance allowed per course.
