<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Gen\Gen;
use App\Shared\Job\Stamp\ArchiveStamp;
use App\Shared\Job\Stamp\AvailableAtStamp;
use App\Shared\Job\Stamp\DelayStamp;
use App\Shared\Job\Stamp\EvaluationStamp;
use App\Shared\Job\Stamp\IdStamp;
use App\Shared\Job\Stamp\InstructionStamp;
use App\Shared\Job\Stamp\PriorityStamp;
use App\Shared\Job\Stamp\RetryScheduledStamp;
use App\Shared\Job\Stamp\RetryStamp;
use App\Shared\Job\Stamp\SyncStamp;

class Instructor
{
    public function __invoke(Envelope $envelope): Envelope
    {
        if ($envelope->count(InstructionStamp::class) === 0) {
            if (!$envelope->has(IdStamp::class)) {
                $envelope = $envelope->add(new IdStamp(id: Gen::uuid4()));
            }

            if (!$envelope->has(PriorityStamp::class)) {
                $envelope = $envelope->add(new PriorityStamp(priority: Priority::default()));
            }

            if (!$envelope->has(RetryStamp::class)) {
                $envelope = $envelope
                    ->add(new RetryStamp(60))
                    ->add(new RetryStamp(300))
                    ->add(new RetryStamp(900))
                    ->add(new RetryStamp(3600))
                ;
            }

            if ($envelope->has(SyncStamp::class)) {
                $envelope = $envelope->add(InstructionStamp::sync());
            } else {
                $envelope = $envelope->add(InstructionStamp::async());
                $delay = $envelope->get(DelayStamp::class);
                if ($delay) {
                    $envelope = $envelope->add(AvailableAtStamp::ofDelay($delay->delay()));
                }
            }

            return $envelope;
        }

        if ($envelope->count(InstructionStamp::class) > $envelope->count(EvaluationStamp::class)) {
            return $envelope;
        }

        $evaluation = $envelope->get(EvaluationStamp::class);
        if (!$evaluation) {
            return $envelope;
        }

        $instruction = $evaluation->instruction();

        if ($instruction->equals(Instruction::async())) {
            return $envelope->add(InstructionStamp::sync());
        }

        if ($instruction->equals(Instruction::sync())) {
            if ($evaluation->success()) {
                return $envelope->add(InstructionStamp::done());
            }

            if ($envelope->count(RetryStamp::class) === $envelope->count(RetryScheduledStamp::class)) {
                return $envelope->add(InstructionStamp::fail());
            }

            $retryOffset = $envelope->count(RetryScheduledStamp::class);
            $retry = $envelope->all(RetryStamp::class)[$retryOffset];

            return $envelope
                ->add(AvailableAtStamp::ofDelay($retry->delay()))
                ->add(RetryScheduledStamp::ofDelay($retry->delay()))
                ->add(InstructionStamp::async())
            ;
        }

        if ($instruction->equals(Instruction::done())) {
            if ($envelope->has(ArchiveStamp::class)) {
                return $envelope->add(InstructionStamp::archive());
            }
            return $envelope;
        }

        return $envelope;
    }
}
