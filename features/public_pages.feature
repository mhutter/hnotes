Feature: Public pages

  In order to get information about the application
  As an unauthenticated visitor
  I need to be able to see the public pages

  Background:
    Given the app is started
    And I am not authenticated

  Scenario: About page
    When I visit "/about"
    Then the request should be successful
    And the title should be "About - hNotes"
    And the body should contain "About hNotes"

  Scenario: Impressum
    When I visit "/impressum"
    Then the request should be successful
    And the title should be "Impressum - hNotes"
    And the body should contain "Impressum"
