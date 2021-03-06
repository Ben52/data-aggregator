Data Aggregator Changelog
=============================

### 1.0-beta2 – Minor enhancements, code cleanup and reorganization

* Refactor deletes from LAKE so they can run every five minutes instead of hourly
* Move titles into all listing endpoints, where previously they were only indexed in search
* Add `alt_text` to `thumbnail` block of all resources


ARTWORKS

* The following modifications have been made to the API schema:
  - `has_multimedia_resources` - Added, whether this artwork has any associated microsites, digital publications, or documents tagged as multimedia
  - `has_educational_resources` - Added, whether this artwork has any documents tagged as educational
  - `theme_titles` - Added, the names of all thematic publish categories related to this artwork


EVENTS

* Add fields to support sales.artic.edu
* The following modifications have been made to the API schema:
  - `is_admission_required` - Added, whether admission to the museum is required to attend this event
  - `ticketed_event_id` - Added, unique identifier of the event in the ticketing system this website event is tied to
  - `survey_url` - Added, URL to the survey associated with this event
  - `email_series` - Added, the email series associated with this event
  - `door_time` - Added, the time the doors open for this event


### 1.0-beta1 – Final cleanup before beta and bug fixes

* Upgrade to Laravel 5.6
* Unpublished all documentation
* Performance improvements to artwork endpoints
* Add hourly command to delete unpublished collections data
* Refactor transformations from source systems into a shared code set to reduce duplication
* Add functional tests to monitor artwork search behavior
* Clean up duplication in casting model value types
* Update models when related records are updated. E.g., update artwork `is_on_view` when the gallery `is_closed` flag is updated.
* Standardize how source dates are output in the API
* Add `info` and `config` block to the bottom of each request, to convert version number and URLs needed to use paths that reference other systems


ARTWORKS

* Output `fiscal_year` values
* The following modifications have been made to the API schema:
  - `technique_id` - Added, unique identifier of the preferred technique term for this work
  - `alt_technique_ids` – Added, unique identifiers of all other non-preferred technique terms for this work
  - `technique_titles` – Added, the names of all technique terms related to this artwork
* The following includes are now available in the /artworks endpoint:
  - `catalogue_pivots` – Renamed from `catalogues` to more accurately reflect what it returns


ASSETS (including IMAGES, SOUNDS, VIDEOS, and TEXTS)

* The following modifications have been made to the API schema:
  - `alt_text` – Added, alternative text for the asset to describe it to people with low or no vision
  - `copyright_notice` - Added, statement notifying how the asset is protected by copyright. Applies to the asset itself, not artwork it may be related to.


SELECTIONS

* The following modifications have been made to the API schema:
  - `copy` – Renamed from `content` to more accurately name its content


DEPRECATED ENDPOINTS

* `artwork-dates` – Removed due to lack of use cases for accessing or searching this data directly
* `artwork-dates/{id}`
* `pages` – Deprecated from the Web CMS API
* `pages/{id}`


NEW ENDPOINTS

* A number of new resources types have been made available in the Web CMS, and we've added corresponding endpoints in the Data Hub:
  - `generic-pages`
  - `generic-pages/{id}`
  - `press-releases`
  - `press-releases/{id}`
  - `research-guides`
  - `research-guides/{id}`
  - `educator-resources`
  - `educator-resources/{id}`
  - `digital-catalogs`
  - `digital-catalogs/{id}`
  - `printed-catalogs`
  - `printed-catalogs/{id}`


### 0.13 – Tweak search and filters

* Refactor CategoryTerm to store all their data in the same table and use scope models to make distinctions between the two, rather than combining the table data in a view
* Make website pageviews influence relevancy score in artwork search
* Add `technique` term fields


ARTWORKS

* The following modifications have been made to the API schema:
  - `technique_ids` - Added, unique identifiers of all technique terms for this work
  - `artist_title` - Added, names of the preferred artist/culture associated with this work
  - `date_dates` - Removed, replaced with the `dates` include added in 0.12, as this list doesn't provide any of the pivot fields (preferred, start date, end date, date type qualifier, etc)
  - `link_ids` - Removed, as a link is not a unique resource but is represented by the `content` field on assets


CATEGORY-TERMS (including CATEGORIES and TERMS)

 * The following modifications have been made to the API schema:
   - `term_id` - Removed, as this is duplicating the `id` field
   - `category_id` - Removed, as this is duplicating the `id` field
   - `term_type_id` - Removed, replaced with `subtype`, since this list is short and very static
   - `subtype` - Added, takes one of the following values: classification, material, technique, style, subject, department, theme


ASSETS (including IMAGES, SOUNDS, VIDEOS, and TEXTS)

 * Added `content.keyword` field to search, for filtering assets with URLs
 * The following modifications have been made to the API schema:
   - `is_multimedia_resource` - Added, whether this resource is considered to be multimedia
   - `is_educational_resource` - Added, whether this resource is considered to be educational
   - `is_teacher_resource` - Added, whether this resource is considered to be educational


DEPRECATED ENDPOINTS

 * Links have been removed from the API entirely, as a link is not a unique resource but is represented by the `content` field on assets.
 * Term types models have been removed and replaced with a static string list
 * Removed `/artworks/{id}/terms` since it doesn't provide "pivot" information about which terms are preferred
 * These endpoints have been removed:
   - `/links`
   - `/links/{id}`
   - `/links/{id}/pull`
   - `/term-types`
   - `/term-types/{id}`
   - `/term-types/{id}/pull`
   - `/artworks/{id}/terms`


### 0.12 – Finish integration with new collections data

* Add and fill in the final round of collections data integration
* Further refactor import commands for consistency

ARTWORKS
* Add Materials terms
* Replace production-quality faked terms with real data
* Reformat department_id to follow same conventions as /categories IDs, since departments are a subset of category records. (E.g., PC-##)
* Add `*.keyword` fields to Elasticsearch for all `*_title[s]` fields
* Add *_ids fields to provide all IDs of fields that are split between preferred and alternate fields
* Fill in multiple titles per artwork
* Fill in multiple images per artwork
* Fill in multiple agents per artwork
* Fill in multiple places per artwork
* Fill in multiple assets per artwork
* Fill in multiple dates per artwork
* The following modifications have been made to the API schema:
  - `alt_classification_ids` - Renamed from `alt_classificaiton_ids` to correct type
  - `material_id` - Added, unique identifier of the preferred material term for this work
  - `alt_material_ids` - Added, unique identifiers of all other non-preferred material terms for this work
  - `material_ids` - Added, unique identifiers of all material terms for this work
  - `material_titles` - Added, the names of all material terms related to this artwork
  - `artist_ids` - Added, unique identifier of all artist/cultures associated with this work
  - `style_ids` - Added, unique identifiers of all style terms for this work
  - `classification_ids` - Added, unique identifiers of all classification terms for this work
  - `subject_ids` - Added, unique identifiers of all subject terms for this work
* The following `include`s are now available in the `/artworks` endpoint:
  - `artist_pivots` - Provides metadata about the agents in the `artists` include, e.g., role, preferred, etc.
  - `place_pivots`
  - `dates` - While not new, this include has now been filled in with data

ASSETS (including IMAGES, SOUNDS, VIDEOS, and TEXTS)
* Note which assets should be made available in multimedia, educational resources, etc.
* Added `/assets/search` that targets images, videos, sounds, and texts
* The following modifications have been made to the API schema:
  - `content` - Added, text of URL of the contents of this asset
  - `is_multimedia_resource` - Added, whether this resource is considered to be multimedia
  - `is_educational_resource` - Added, whether this resource is considered to be educational
  - `is_teacher_resource` - Added, whether this resource is considered to be educational

EXHIBITIONS
* Added installation shots and PDFs related to exhibitions
* The following modifications have been made to the API schema:
  - `image_iiif_url` - Removed, in favor of clients creating the URL based on `image_id`
  - `alt_image_ids` - Added, Unique identifiers of all non-preferred images of this exhibition.
  - `document_ids` - Added, Unique identifiers of assets that serve as documentation for this exhibition

TERMS
* Replace production-quality fake terms data with real data
* The following modifications have been made to the API schema:
  - `type` - Removed, `term_type_id` takes its place
  - `term_type_id` - Added, unique identifier of term type

TOURS
* Make tours findable by the artwork and artist names of the works included in the tour
* The following modifications have been made to the API schema:
  - `artwork_titles` - Added, names of the artworks featured in this tour's tour stops
  - `artist_titles` - Added, names of the artists of the artworks featured in this tour's tour stops
  - `tour_stop_titles` - Removed, `artwork_titles` takes its place

PRODUCTS
* Make note of whether a shop product is currently available on their website
* The following modifications have been made to the API schema:
  - `is_active` - Added, whether this product is currently available on the shop website

NEW ENDPOINTS
* The following endpoints have been added:
  - `agent-roles`
  - `agent-roles/{id}`
  - `artwork-place-qualifiers`
  - `artwork-place-qualifiers/{id}`
  - `artwork-dates`
  - `artwork-dates/{id}`
  - `artwork-date-qualifiers`
  - `artwork-date-qualifiers/{id}`
  - `term-types`
  - `term-types/{id}`

TO BE DEPRECATED IN 0.13
* `/artworks` - `date_dates` - Replaced with the `dates` include, as this list doesn't provide any of the pivot fields (preferred, start date, end date, date type qualifier, etc)
* `/links` - These entire endpoints will be removed, as a link is not a unique resource but is represented by the `content` field on assets
* `/links/{id}`
* `/links/{id}/pull`
* `/artworks` - `link_ids`

### 0.11 – Integrate Web CMS content and adjust search for the Mobile App

* Make adjustments to search behavior for the Mobile app
* Automatically generate Swagger documentation to keep it in-line with the codebase
* Move fake model behavior to a reusable trait
* Add endpoints to allow source systems to let Data Hub know when a single record has been updated, to accommodate real-time updates. Only the Web CMS and Collections endpoints are set up to be able to use this functionality.
* Add job queue for asynchronous processing of tasks
* Import content from Web CMS and schedule incremental imports every five minutes
* Refactor import commands to reduce duplication

ARTWORKS
* Provide relationship to documents, which are assets that are _about_ artworks, as opposed to images which are _of_ artworks
* Remove copyright representative from API
* The following modifications have been made to the API schema:
  - `medium_display` - Renamed from `medium` to make room for medium-type terms, for filtering
  - `is_highlighted_in_mobile` – Removed, since it's unused by mobile and doesn't influence `is_boosted` logic
  - `copyright_representative_ids` – Removed, since our website designs don't require us to pull this data
  - `copyright_representative_titles` – Removed, since our website designs don't require us to pull this data
  - `image_iiif_url` – Removed, in favor of clients using `image_id` with any of the LAKE image servers
  - `document_ids` – Added, an array of `assets` that are _about_ the work, as opposed to images which are _of_ a work

LEGACY EVENTS
* Clean up extra HTML styling tags and attributes from descriptions

PRODUCTS
* Display `image_url` as ImgIX URL instead of image on Shop website

ARTICLES
* Parse out description text and main image from article content

NEW ENDPOINTS
* As part of new functionality to provide an opportunity for source systems to let us know about content updates, the following endpoints have been added:
  - `/artworks/{id}/pull`
  - `/agents/{id}/pull`
  - `/venues/{id}/pull`
  - `/agent-places/{id}/pull`
  - `/artwork-catalogues/{id}/pull`
  - `/departments/{id}/pull`
  - `/artwork-types/{id}/pull`
  - `/categories/{id}/pull`
  - `/agent-types/{id}/pull`
  - `/places/{id}/pull`
  - `/galleries/{id}/pull`
  - `/exhibitions/{id}/pull`
  - `/assets/{id}/pull`
  - `/images/{id}/pull`
  - `/videos/{id}/pull`
  - `/links/{id}/pull`
  - `/sounds/{id}/pull`
  - `/texts/{id}/pull`
  - `/catalogues/{id}/pull`
  - `/terms/{id}/pull`
  - `/tags/{id}/pull`
  - `/locations/{id}/pull`
  - `/hours/{id}/pull`
  - `/closures/{id}/pull`
  - `/web-exhibitions/{id}/pull`
  - `/events/{id}/pull`
  - `/articles/{id}/pull`
  - `/selections/{id}/pull`
  - `/web-artists/{id}/pull`
  - `/pages/{id}/pull`


### 0.10.1 – Hotfix to remove ELasticsearch functionality from testing application environment

### 0.10 – CMS API integration and image thumbnail metadata

* Create `/category-terms` endpoint that combines `/categories` and `/terms` into a single endpoint
* Add new endpoints for all web CMS content, and index them in search
* Add image thumbnail metadata to search results and listing endpoints, as we work towards clients having everything they need to display search results in a UI without needing to make extra calls
* Upgrade to Elasticsearch 6.0

SEARCH RESULTS AND ALL ENDPOINTS
 * The following modifications have been made to the API schema:
   - `thumbnail` - Added, JSON blob with metadata about the image that represents a search result. Currently only artworks are returning this data. We'll be filling this in for other resources in future sprints. JSON blob includes:
     - `url` - URL to the static image, or base URL to the image treatment service
     - `type` - What the `image_url` points to
     - `lqip` - Base64 string of a low-quality image placeholder
     - `width` - Maximum width of the image
     - `height` - Maximum height of the image

SEARCH
 * Boosts agents higher than unweighted items. Also applies to autocomplete.

LEGACY EVENTS
 * Remove HTML entities from output

ARTWORKS
 * The following modifications have been made to the API schema:
   - `category_ids` - Changed to alphanumeric IDs in the format 'PC-###'
   - `style_ids` - Changed to alphanumeric IDs in the format 'TM-####'
   - `alt_style_ids` - Changed to alphanumeric IDs in the format 'TM-####'
   - `classification_ids` - Changed to alphanumeric IDs in the format 'TM-####'
   - `alt_classification_ids` - Changed to alphanumeric IDs in the format 'TM-####'
   - `subject_ids` - Changed to alphanumeric IDs in the format 'TM-####'
   - `alt_subject_ids` - Changed to alphanumeric IDs in the format 'TM-####'

IMAGES
 * The following modifications have been made to the API schema:
   - `width` - Added. Native width of the image
   - `height` - Added. Native height of the image
   - `lqip` - Added. Low-quality image placeholder (LQIP). Currently a 5x5-constrained, base64-encoded GIF.

CATEGORIES
 * The following modifications have been made to the API schema:
   - `id` - Changed to alphanumeric IDs in the format 'PC-###'
   - `parent_id` - Changed to alphanumeric IDs in the format 'PC-###'

TERMS
 * The following modifications have been made to the API schema:
   - `id` - Changed to alphanumeric IDs in the format 'TM-###'

GALLERIES
 * The following modifications have been made to the API schema:
   - `category_ids` - Changed to alphanumeric IDs in the format 'PC-###'

ASSETS (including IMAGES, SOUNDS, VIDEOS, TEXTS, and LINKS)
 * The following modifications have been made to the API schema:
   - `category_ids` - Changed to alphanumeric IDs in the format 'PC-###'

TOUR STOPS
* `tour_id` now correctly displays a value

NEW ENDPOINTS
* Integration with Web CMS provides a number of endpoints, but all are currently empty:
  - `/tags`
  - `/tags/{id}`
  - `/tags/search`
  - `/locations`
  - `/locations/{id}`
  - `/locations/search`
  - `/hours`
  - `/hours/{id}`
  - `/hours/search`
  - `/closures`
  - `/closures/{id}`
  - `/closures/search`
  - `/web-exhibitions`
  - `/web-exhibitions/{id}`
  - `/web-exhibitions/search`
  - `/events`
  - `/events/{id}`
  - `/events/search`
  - `/articles`
  - `/articles/{id}`
  - `/articles/search`
  - `/selections`
  - `/selections/{id}`
  - `/selections/search`
  - `/web-artists`
  - `/web-artists/{id}`
  - `/web-artists/search`
  - `/pages`
  - `/pages/{id}`
  - `/pages/search`


### 0.9 – Autocomplete, shop data and support for mobile app

* Fill in products and shop categories data from the museum shop
* Add unit tests to verify that the API is serving fields used by the mobile app
* Silent output on successful scheduled commands

AUTOCOMPLETE
* Refactor `/autocomplete` endpoint to simplify output
* Only provide boosted records as results
* Allow clients to specify which resources to include in the results
* Find matches for both sort titles and regular titles
* Allow for fuzzy matching on queries greater than 5 characters
* Account for articles, e.g., "the", "an" and "a"

ARTWORKS
* Remove committees from API output. We use the data to calculate other fields, but we don't have a use case from our clients to use this data directly.
* Rename ObjectType to ArtworkType, to match our naming conventions
* Add `fiscal_year` for use in Recent Acquisitions filter on website
* Add `sound_ids`, `video_ids`, `link_ids` and `text_ids` to artworks
* The following modifications have been made to the API schema:
  - `artwork_type_title` - Renamed from `object_type_title` to match the naming of this model
  - `artwork_type_id` - Renamed from `object_type_id` to match the naming of this model
  - `committee_titles` - Removed from the API
  - `sound_ids` - Added, unique identifiers of the audio about this work
  - `video_ids` - Added, unique identifiers of the videos about this work
  - `link_ids` - Added, unique identifiers of the links about this work
  - `text_ids` - Added, unique identifiers of the texts about this work
  - `style_titles` - Added, the names of all style terms related to this artwork
  - `classification_titles` - Added, the names of all classification terms related to this artwork
  - `subject_titles` - Added, the names of all subject terms related to this artwork

EXHIBITIONS
* Add hero images from existing marketing site
* Properly output `gallery_title`
* Properly import `gallery_id`
* Pull exhibition descriptions from existing website if we didn't already have one
* The following modifications have been made to the API schema:
  - `legacy_image_desktop_url` - Added, URL to the desktop hero image from the legacy marketing site
  - `legacy_image_mobile_url` - Added, URL to the mobile hero image from the legacy marketing site

AGENTS
* Fill in alternate names with real data
* The following modifications have been made to the API schema:
  - `sort_title` - Added, sortable name, typically with last name first
  - `agent_type_title` - Renamed from `agent_type` to match schema used by other models

TERMS
* Add search endpoint for terms at `/terms/search`

PRODUCTS
* The following modifications have been made to the API schema:
  - `title_sort` - Added, the sortable version of the name of this product
  - `title_display` - Removed, since we're not getting a title catered to display from the Shop API
  - `parent_id` - Added, unique identifier of this product's parent
  - `category_id` - Added, replaces the array of `category_ids` until we get cleaner data
  - `category_ids` - Removed, until we understand how to navigate this data from the shop API
  - `web_url` - Renamed from `link` to match schema used by other models
  - `external_sku` - Added, numeric product identification code of a machine-readable bar code, when the customer sku differs from our internal one
  - `image_url` - Renamed from `image` to match schema used by other models
  - `is_on_sale` - Removed, until we understand how to determine this from the shop API
  - `rating` - Removed, until the data is provided to from the shop API
  - `review_count` - Removed, until the data is provided to from the shop API
  - `item_sold` - Removed, until the data is provided to from the shop API
  - `inventory` - Added, number indicating how many items remain in our inventory
  - `sale_price` - Added, number indicating how much the product costs on sale to the customer
  - `member_price` - Added, number indicating how much the product costs members
  - `aic_collection` - Added, whether the item is an AIC product
  - `gift_box` - Added, whether the item can be wrapped in a gift box
  - `recipient` - Added, category indicating who the product is intended for. E.g., 'Anyone', 'ForHim', 'ForHer', etc.
  - `holiday` - Added, whether the product is a holiday item
  - `architecture` - Added, whether the product is an architectural item
  - `glass` - Added, whether the item is made of glass
  - `choking_hazard` - Added, whether this product is a choking hazard
  — `x_shipping_charge` - Added, number indicating the additional shipping charge for this item, in US Dollars.
  - `back_order` - Added, whether this product has been back ordered
  - `back_order_due_date` - Added, date representing when this item is expected to be back in stock
  - `artist_ids` - Added, unique identifiers of the artists represented in this item

SHOP CATEGORIES
* The following modifications have been made to the API schema:
  - `web_url` - Renamed from `link` to match schema used by other models
  - `type` - Removed, until the data is provided to from the shop API
  - `source_id` - Removed, until the data is provided to from the shop API


### 0.8 – Clean up for consistency, and further feature additions to support the website and mobile app

* Add `*_title` to all name-of fields for consistency
* Accept `q` and `query` in the same search request
* Boost artists, to provide better data to the "Featured result" option on the website. We're boosting all artists in the set of boosted artworks, along with the top 100 viewed artists on our website in 2017.
* Refactor departments to use departmental publish categories in CITI rather than our internal department structure.
* Add includes for `sites` to `/agents`, `/artworks` and `/exhibitions`
* Split `/galleries` and `/places` with a more reliable condition
* Remove deprecated `theme` model, which isn't output anywhere in the API
* Split `/events` up into `/ticketed-events` and `/legacy-events` endpoints, to make room for canonical events from the new website
* Remove `/members` endpoint
* Add `button_url`, `button_text` and `web_url` to `/legacy-events`
* Provide functionality to pass aggregation parameters to search endpoints
* Add multi search functionality to allow multiple queries to be sent in one request
* Make tours discoverable by the names of their tour stops
* Add `tour_ids` to `/tour-stops`
* Rename `/tours` include from `stops` to `tour-stops`

ARTWORKS ENDPOINT
* Fix output of `medium` field
* Properly fill in rights flags
* The following modifications have been made to the API schema:
  - `department_title` - Renamed from `department` to match schema used by other fields
  - `object_type_title` - Renamed from `object_type` to match schema used by other fields
  - `is_in_gallery` - Removed, in favor of `is_on_view` that accounts for gallery closures
  - `object_type_title` - Renamed from `object_type` to match schema used by other fields

VENUES ENDPOINT
* The following modifications have been made to the API schema:
  - `agent_title` - Renamed from `agent` to match schema used by other fields
  - `exhibition_title` - Renamed from `exhibition` to match schema used by other fields

AGENT PLACES ENDPOINT
* The following modifications have been made to the API schema:
  - `agent_title` - Renamed from `agent` to match schema used by other fields
  - `place_title` - Renamed from `place` to match schema used by other fields

ARTWORK CATALOGUES ENDPOINT
* The following modifications have been made to the API schema:
  - `artwork_title` - Renamed from `artwork` to match schema used by other fields
  - `catalogue_title` - Renamed from `catalogue` to match schema used by other fields

TOUR STOPS ENDPOINT
* The following modifications have been made to the API schema:
  - `artwork_title` - Renamed from `artwork` to match schema used by other fields

NEW ENDPOINTS
* Additionally, the following endpoints have been added to the API:
  - `/artworks/boosted` – Renamed from `/artworks/essentials`, which has been deprecated. This naming matches logic we released in 0.7 to create a common mechanism by which resources can provide boosted results.
  - `/agents/boosted` – We're now boosting artists in addition to artworks. This endpoint provides a view of just boosted artists.
  - `/legacy-events` and `/ticketed-events` – The `/events` endpoint has been split up into two separate endpoints, more clearly naming what data it's serving. `/legacy-events` are from the existing website for use by the mobile app. `/ticketed-events` are the small set of events in our ticketing system for use by the website CMS. These name changes also affect the resource names and endpoints for our search. After the website launches, `/legacy-events` will be deprecated and we'll work with the mobile team to make the transition. This change has been made to make room for a single, canonical list of events that will be provided by the new website.
  - The `/members` endpoint has been removed.

DEPRECATED SEARCH PARAMETERS
* As planned, the following search parameters have been removed from the API:
  - `type` – Replaced with `resources`. This parameter is no longer supported in later versions of Elasticsearch.
  - `_source` – Replaced with `fields`. This parameter duplicates functionality provided by `fields`.
  - `facets` - Replaced with `aggregations`. This parameter opens up built in Elasticsearch aggregation functionality to our API


### 0.7 – Add functionality to support website and mobile App development

* Add general import:all and import:daily commands to run imports from all sources
* Adjust task scheduling to make automatic imports function properly
* Enhance search, artworks and exhibitions to support website and mobile app, as follows:

SEARCH ENHANCEMENTS
* Avoid adjusting relevance if `sort` is set
* Adjust simple searches to only search specified fields
* Allow searching across multiple resources
* Add `is_boosted` field to search output, to help inform "Featured result"
* Generalize definition of is_boosted functionality across all resources
* Add ability to search resources that are subsets of others, like artists and galleries

ARTWORK ENDPOINT
* Restructure terms and catalogues raisonne to be a first-class resource rather than a list of strings
* Seed terms data with static production export
* Add catalogue raisonne data from LPM
* Add flags to indicate rights usage
* The following modifications have been made to the API schema:
  - `alt_titles` - Renamed from `alternative_titles` to match schema used by other fields
  - `is_zoomable` - Added, whether images of the work are allowed to be displayed in a zoomable interface.
  - `max_zoom_window_size` - Added, the maximum size of the window the image is allowed to be viewed in, in pixels.
  - `fiscal_year` - Added, the fiscal year in which the work was acquired.
  - `artist_ids` - Removed, in favor of two separate fields to specify a preferred artists and all others.
  - `artist_id` - Added, unique identifier of the preferred artist associated with the work
  - `alt_artist_ids` - Added, unique identifiers of the non-preferred artists/cultures associated with the work
  - `catalogue_titles` - Removed
  - `artwork_catalogue_ids` - Added, represents all the catalogues this work is included in. This isn't an exhaustive list of publications where the work has been mentioned. For that, see `publication_history`.
  - `style_id` - Added, unique identifier of the preferred style term for the work
  - `alt_style_ids` - Added, unique identifiers of all other non-preferred style terms for the work
  - `classification_id` - Added, unique identifier of the preferred classification term for the work
  - `alt_classification_ids` - Added, unique identifiers of all other non-preferred classification terms for the work
  - `subject_id` - Added, unique identifier of the preferred subject term for the work
  - `alt_subject_ids` - Added, unique identifiers of all other non-preferred subject terms for the work
  - `image_id` - Renamed from `preferred_image_id`
  - `image_iiif_url` - Renamed from `preferred_image_iiif_url`
  - `alt_image_ids` - Renamed from `image_ids` and refactored to omit the preferred image
  - `alt_image_iiif_urls` - Renamed from `image_iiif_urls` and refactored to omit the preferred image

EXHIBITIONS ENDPOINT
* Add new fields to support mobile app:
  - `short_description` - Brief explanation of what this exhibition is
  - `web_url` - URL to this exhibition on our website

NEW ENDPOINTS
* Additionally, the following endpoints have been added to the API:
  - `/terms` – Get a list of all terms. Includes all styles, classifications and subjects bundled together, differentiated by `type`.
  - `/terms/{id}` – Get a specific term with the given ID.
  - `/artwork/{id}/terms` – Get all the terms for an artwork.
  - `/catalogues` – Get a list of all catalogues raisonné that we know about.
  - `/catalogues/{id}` – Get a specific catalogue raisonné with the given ID.
  - `/artwork-catalogues` – Get all the pivot catalogues raisonné between artworks and catalogues. The pivot models include page numbers, state/edition, and whether this is the preferred catalogue for the artwork.
  - `/artwork-catalogues/{id}` – Get a specific pivot catalogue.
  - `/artworks/{id}/artwork-catalogues` – Get all the pivot catalogues for a given artwork.

NEW SEARCH PARAMETERS
* Additionally, the following parameters have been added to our search endpoints, with plans to deprecate some existing ones
  - `resources` – An array to identify the types of data to return. Options match the names of available endpoints. For example "artworks", "galleries", etc.
  - `type` – This parameter is no longer supported in later versions of Elasticsearch, so we plan to deprecate this in 0.8.
  - `fields` – An array of field names to return in your search results. Can be set to any field name that's returned in our API. Can also be set to `true` to return all fields, which is useful for debugging.
  - `_source` – This parameter duplicates functionality provided by `fields`, so we plan to deprecate this in 0.8.


### 0.6 – Fill in Collections data

* Upgrade to Laravel 5.5
* Clean up Fillable logic to reduce redundancy
* Add db:reset command to drop all tables
* Change all CITI IDs to signed integers in database, to account for low negative number IDs from CITI places
* Add `alternate_titles` field to API schema for artworks and agents

EXHIBITIONS ENDPOINT
* Add start dates and end dates:
  - aic_start_at - Date the exhibition opened at the Art Institute of Chicago
  - aic_end_at - Date the exhibition closed at the Art Institute of Chicago
  - start_at - Date the exhibition opened across multiple venues
  - end_at - Date the exhibition closed across multiple venues
* Remove array of images in favor of a single, preferred image:
  - image_id - Unique identifier of the image to use to represent this exhibition
  - image_iiif_url - IIIF URL of the image to use to represent this exhibition
* Add additional fields:
  - status - Whether the exhibition is open or closed
* Populate venues data, which fills in `venue_ids` and `/venues`, `/venues/{id}` and `/exhibitions/{id}/venues` endpoints
* Populate artworks data, which fills in `artwork_ids` and `/exhibitions/{id}/artworks` endpoint
* Show related artists via related Static Archive Sites
* Add `short_description` and `web_url` and import from existing website
* Fill in the gallery_id based on a string name (hotfix until more reliable data is available from LAKE)

PLACES ENDPOINT
* Create `/places` endpoints that act as parents to galleries. Add `/places` and `/places/{id}` endpoints
* Populate Agent Place data, which fills in `agents/{id}/places`, `/agent-places` and `/agent-places/{id}` endpoints
* Fill in gallery type with "AIC Gallery" for any gallery that is associated with an artwork (hotfix until more reliable data is available from LAKE)

ARTWORKS ENDPOINT
* Fill in the gallery_id based on a string name (hotfix until more reliable data is available from LAKE)
* Rename `gallery` to `gallery_title`
* Deprecate `is_in_gallery` in favor of `is_on_view`, which checks if the artwork is in a gallery, and that the gallery is open


### 0.5 – Library, Archives, dominant colors and improve search

* Add Etags to all API output
* Add dominant color information to all images
* Separate Elasticsearch index into separate indexes per resource, for future compatibility
* Refactor agents to store all in a single table, while still providing separate endpoints by type
* Tie Agents to their corresponding ULAN URIs
* Add ability to sort search results
* Abstract portions of code common between APIs into the `data-hub-foundation` package
* Add Library Terms and Materials data to the aggregator
* Add images from the Ryerson & Burnham Library Image Archive to the aggregator
* Add missing relationships to Artwork endpoint—DSC Sections, Mobile Tour Stops, dominant color of preferred image
* Add missing relationships to Artists endpoint—Artworks
* Add missing relationships to Exhibitions endpoint—Events


### 0.4 – Digital Catalogues, static sites and mobile

* Refactor artists endpoint to return all agents that are marked as a creator for an artwork
* Import real Digital Catalogue data
* Import static site archive data
* Import events from current website as production-quality sample data
* Add related exhibitions to events
* Add long and short description to events
* Clean up mobile tour API output
* Reduce duplication in transformer logic
* Reduce duplication in seeder logic
* Reduce duplication in Asset model logic
* Provide Faker as a service to all unit tests and factories


### 0.3 – Clean up:

* Import processes to remove duplicate relationships
* Seeding to create fake records with IDs out of the range of real data, so they can live side-by-side
* Update `db:seed` command to not truncate tables during seeding
* Create `db:cleanseed` command to delete seeded data
* Add types to field documentation
