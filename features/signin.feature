Feature: User sessions
  In order to access their account
  As a user
  I need to be able to log into the website

  Scenario: Login
    Given I am on "/login"
    And I should see "Sign in"
    When I fill in "_username" with "test@webdna.io"
    And I fill in "_password" with "mysecurepassword"
    And I press "Login"
    Then I should be on "/dashboard/website/add"
    When I follow "Sign out"
    Then I should be on "/user/logout_success"