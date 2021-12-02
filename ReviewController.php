<?php
\RigorTalks\DocManager\Controller\ReviewController;

namespace RigorTalks\DocManager\Controller;

class ReviewController extends BaseController
{
    public function update($review_id, array $data = array())
    {
        $review = $this->get($review_id);
        $date = new \DateTime;
        $dateFormat = $date->format("Y-m-d, H:i:s");
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
            //$review->setUpdated($dateFormat);
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

