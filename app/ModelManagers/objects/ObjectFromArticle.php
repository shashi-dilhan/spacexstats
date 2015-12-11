<?php

namespace SpaceXStats\ModelManagers\Objects;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SpaceXStats\Library\Enums\ObjectPublicationStatus;
use SpaceXStats\Library\Enums\MissionControlType;
use SpaceXStats\Models\Object;

class ObjectFromArticle extends ObjectCreator {
    public function isValid($input) {
        $this->input = $input;
        return $this->validate($this->object->rules);
    }

    public function create() {
        DB::transaction(function() {
            $this->object = Object::create([
                'user_id'               => Auth::id(),
                'type'                  => MissionControlType::Article,
                'title'                 => $this->input['title'],
                'size'                  => strlen($this->input['article']),
                'article'               => $this->input['article'],
                'summary'               => $this->input['summary'],
                'cryptographic_hash'    => hash('sha256', $this->input['article']),
                'originated_at'         => Carbon::now(),
                'status'                => ObjectPublicationStatus::QueuedStatus
            ]);

            $this->createMissionRelation();
            $this->createTagRelations();
            $this->createPublisherRelation();
        });

        return $this->object;
    }
}