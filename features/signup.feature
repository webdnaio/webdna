Feature: Sign up
  In order to sign up an account
  I need to submit a registration form

  Scenario: Registration with sign in form
    Given I am an interested user with email "test@webdna.io"
    Given I am on "/register"
    And I should see "CREATE ACCOUNT"
    When I fill in "webdna_user_registration[email]" with "test@webdna.io"
    And I fill in "webdna_user_registration[plainPassword]" with "mysecurepassword"
    And I press "Register"
    Then I should be on "/user/register_success"
    And I should see "We'd love to have you here"
    Then I confirm my registration
