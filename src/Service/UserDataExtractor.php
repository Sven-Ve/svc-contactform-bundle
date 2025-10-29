<?php

declare(strict_types=1);

/*
 * This file is part of the svc/contactform-bundle.
 *
 * (c) 2025 Sven Vetter <dev@sv-systems.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Svc\ContactformBundle\Service;

/**
 * Service to extract user data for pre-filling contact forms.
 */
class UserDataExtractor
{
    /**
     * Extract email and name data from a user object.
     *
     * @param mixed $user User object that may implement various methods
     *
     * @return array{email: string, name: string}
     */
    public function extractUserData($user): array
    {
        $data = ['email' => '', 'name' => ''];

        if (!$user) {
            return $data;
        }

        try {
            // Extract email
            if (is_object($user) && method_exists($user, 'getEmail')) {
                $email = $user->getEmail();
                $data['email'] = is_string($email) ? $email : '';
            }

            // Extract name - try nickname first, then first/lastname
            if (is_object($user) && method_exists($user, 'getNickname')) {
                $nickname = $user->getNickname();
                $data['name'] = is_string($nickname) ? $nickname : '';
            } else {
                $nameParts = [];

                if (is_object($user) && method_exists($user, 'getFirstname')) {
                    $firstname = $user->getFirstname();
                    if (is_string($firstname) && !empty($firstname)) {
                        $nameParts[] = $firstname;
                    }
                }

                if (is_object($user) && method_exists($user, 'getLastname')) {
                    $lastname = $user->getLastname();
                    if (is_string($lastname) && !empty($lastname)) {
                        $nameParts[] = $lastname;
                    }
                }

                $data['name'] = implode(' ', $nameParts);
            }
        } catch (\Exception) {
            // Silently handle any exceptions during user data extraction
            // Return empty data if extraction fails
        }

        return $data;
    }
}
