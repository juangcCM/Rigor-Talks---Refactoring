<?php
\RigorTalks\DocManager\Controller\ReviewController;

namespace RigorTalks\DocManager\Controller;

class UpdateReviewUseCase
{
    public function __contruct()
    {
    }

    public function execute()
    {

    }
}

class ReviewController extends BaseController
{
    public function update($review_id, array $data = array())
    {
        $review = $this->get($review_id);
        if ($review->getState() = ReviewStates::IN_PROGRESS) {
            $data['extra'] = serialize(json_decode($data['extra']));
            $review->update($data);
            if (isset($data('score']))
            {
                $review->setScore($data['score']);
                $this->flushManager();
            }
            if (isset($data['idError'])) {
                $review->setIdError($data['idError']);
                $this->flushManager();
            }
            $this->triggerReviewEvent(ReviewEvents::UPDATED, $review);
            $this->getService('superlog_controller')
                ->create(new \DateTime, null, $review->getAuction()->getAssignee()->getUid(),
                    serialize(array('Review' > SuperLogEvents::REVIEW_UPDATED)),
                    serialize(array('auction' => $review->getAuction()->toArray(),
                        'review' => $review->toArray())));
            return $review;
        } else {
            throw new \Exception("The review cannot be updated");
        }
    }
}

