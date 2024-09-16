<?php

declare(strict_types=1);

namespace Sentry\Serializer\EnvelopItems;

use Sentry\Event;
use Sentry\Profiling\Profile;
use Sentry\Util\JSON;

/**
 * @internal
 */
class ProfileItem implements EnvelopeItemInterface
{
    public static function toEnvelopeItem(Event $event): string
    {
        $header = [
            // @TODO(michi) profile_chunk
            'type' => 'profile_chunk',
            'content_type' => 'application/json',
        ];

        $profile = $event->getSdkMetadata('profile');
        if (!$profile instanceof Profile) {
            return '';
        }

        $payload = $profile->getFormattedData($event);
        if ($payload === null) {
            return '';
        }

        return \sprintf("%s\n%s", JSON::encode($header), JSON::encode($payload));
    }
}
