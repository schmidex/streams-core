<?php

namespace Anomaly\Streams\Platform\Stream;

use Illuminate\Support\Facades\Gate;
use Anomaly\Streams\Platform\Support\Facades\Hydrator;
use Anomaly\Streams\Platform\Field\FieldBuilder;
use Anomaly\Streams\Platform\Field\FieldFactory;
use Anomaly\Streams\Platform\Security\Policy;
use Anomaly\Streams\Platform\Stream\Event\StreamWasBuilt;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Workflow\Workflow;

/**
 * Class StreamBuilder
 *
 * @link    http://pyrocms.com/
 * @author  PyroCMS, Inc. <support@pyrocms.com>
 * @author  Ryan Thompson <ryan@pyrocms.com>
 */
class StreamBuilder
{

    /**
     * Build a stream.
     *
     * @param array $stream
     * @return StreamInterface
     */
    public static function build(array $stream)
    {

        /**
         * Build our components and
         * configure the application.
         */
        $fields = array_pull($stream, 'fields', []);

        // (new Workflow([
        //     'step_name' => $closure
        // ]))->process();

        $stream = StreamInput::read($stream);
        $stream = StreamFactory::make($stream);

        $fields = FieldBuilder::build($fields);
        $fields = FieldFactory::make($fields);

        $stream->fields = $fields;

        Gate::policy(get_class($stream->model), $stream->config('policy', Policy::class));

        $stream->fire('built', compact($stream));

        // @todo replace with boot if not booted?
        event(new StreamWasBuilt($stream));

        return $stream;
    }
}
