Feature: Remind
    In order to access my competition
    As somebody who has forgotten my access url
    I need to use the reminder feature

    Scenario: Provide invalid input
        Given I am on the homepage
        When I press "Send me my login"
        Then I should see "Uh oh!"
        And I should see "You need to provide a valid email address."

    Scenario: Provide an email that is not a participant
        Given there is no data in the system
        When I am on the homepage
        And I fill in "email" with "dion@thinkmoult.com"
        And I press "Send me my login"
        Then I should see "Uh oh!"
        And I should see "You do not seem to be a participant in any competitions."

    Scenario: Successfully get my login details sent to me
        Given I am on the homepage
        And I fill in the following:
            | racer1_name        | Racer1              |
            | racer1_weight      | 80                  |
            | racer1_height      | 180                 |
            | racer1_goal_weight | 70                  |
            | racer1_email       | dion@thinkmoult.com |
        And I press "Ready. Set. Go."
        When I am on the homepage
        And fill in "email" with "dion@thinkmoult.com"
        And I press "Send me my login"
        Then I should see "we've sent you your competition access details"
