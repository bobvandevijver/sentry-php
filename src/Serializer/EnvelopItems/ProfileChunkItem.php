<?php

declare(strict_types=1);

namespace Sentry\Serializer\EnvelopItems;

use Sentry\Event;
use Sentry\Profiling\ContinuousProfile;
use Sentry\Util\JSON;

/**
 * @internal
 */
class ProfileChunkItem implements EnvelopeItemInterface
{
    public static function toEnvelopeItem(Event $event): string
    {
        $header = [
            'type' => (string) $event->getType(),
            'content_type' => 'application/json',
        ];

        $profile = $event->getSdkMetadata('profile');
        if (!$profile instanceof ContinuousProfile) {
            return '';
        }

        $payload = $profile->getFormattedData($event);
        if ($payload === null) {
            return '';
        }

        return \sprintf("%s\n%s", JSON::encode($header), JSON::encode($payload));
    }
}
