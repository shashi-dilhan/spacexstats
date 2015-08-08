<?php

namespace SpaceXStats\Creators\Objects;

use SpaceXStats\Creators\Creatable;
use SpaceXStats\Enums\MissionControlType;
use SpaceXStats\Enums\Status;

class ObjectFromArticle extends ObjectCreator implements Creatable {
    public function isValid($input) {
        $this->input = $input;

        $rules = array_intersect_key($this->object->getRules(), []);
        return $this->validate($rules);
    }

    public function create() {
        \DB::transaction(function() {
            $this->object = \Object::create([
                'user_id'               => \Auth::user()->user_id,
                'type'                  => MissionControlType::Article,
                'title'                 => $this->input['title'],
                'size'                  => strlen($this->input['content']),
                'article'               => $this->input['article'],
                'thumb_filename'        => 'text.png',
                'cryptographic_hash'    => hash('sha256', $this->input['content']),
                'originated_at'         => \Carbon\Carbon::now(),
                'status'                => Status::QueuedStatus
            ]);

            $this->createMissionRelation();
            $this->createTagRelations();

            $this->createPublisherRelation();
        });
    }
}