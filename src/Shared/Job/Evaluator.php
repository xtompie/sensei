<?php

declare(strict_types=1);

namespace App\Shared\Job;

use App\Shared\Container\Container;
use App\Shared\Job\Depot\Depot;
use App\Shared\Job\Stamp\EvaluationStamp;
use App\Shared\Job\Stamp\InstructionStamp;
use App\Shared\Timer\Timer;
use Throwable;

class Evaluator
{
    public function __construct(
        private Instructor $instructor,
        private Depot $depot,
        private EnvelopContext $envelopContext,
    ) {
    }

    public function __invoke(Envelope $envelope): Envelope
    {
        $envelope = $this->instructor->__invoke($envelope);

        if ($envelope->count(InstructionStamp::class) === $envelope->count(EvaluationStamp::class)) {
            return $envelope;
        }

        $instructionStamp = $envelope->get(InstructionStamp::class);
        if (!$instructionStamp) {
            return $envelope;
        }

        $instruction = $instructionStamp->instruction();
        if (!$instruction) {
            return $envelope;
        }

        if ($instruction->equals(Instruction::async())) {
            $envelope = $envelope->add(EvaluationStamp::async());
            $this->depot->put($envelope);
        }

        if ($instruction->equals(Instruction::sync())) {
            $timer = Timer::launch();
            $this->envelopContext->set($envelope);
            try {
                Container::container()->call([$envelope->job(), '__invoke']);
                $envelope = $envelope->add(EvaluationStamp::sync(timer: $timer, success: true, error: null));
            } catch (Throwable $exception) {
                $envelope = $envelope->add(EvaluationStamp::sync(
                    timer: $timer,
                    success: false,
                    error:
                    "Uncaught exception: {$exception->getMessage()} "
                    . "in {$exception->getFile()}:{$exception->getLine()} "
                    . "Stack trace:\n {$exception->getTraceAsString()}"
                ));
            } finally {
                $this->envelopContext->clear();
            }
        }

        if ($instruction->equals(Instruction::done())) {
            $envelope = $envelope->add(EvaluationStamp::done());
            $this->depot->done($envelope);
        }

        if ($instruction->equals(Instruction::fail())) {
            $envelope = $envelope->add(EvaluationStamp::fail());
            $this->depot->fail($envelope);
        }

        if ($instruction->equals(Instruction::archive())) {
            $envelope = $envelope->add(EvaluationStamp::archive());
            $this->depot->archive($envelope);
        }

        return $instruction->break() ? $envelope : $this->__invoke($envelope);
    }
}
