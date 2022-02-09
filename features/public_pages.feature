Feature: Public pages
  In order to get information about the application
  As an unauthenticated visitor
  I need to be able to see the public pages

  Scenario: About page
    Given I am not authenticated
    When I visit "/about"
    Then I should see "About hNotes"

  Scenario: Impressum
    Given I am not authenticated
    When I visit "/impressum"
    Then I should see "Impressum"
