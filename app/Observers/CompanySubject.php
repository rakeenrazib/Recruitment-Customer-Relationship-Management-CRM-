<?php

namespace App\Observers;

use Illuminate\Support\Collection;

trait CompanySubject
{
    protected function observerCollectionName(): string
    {
        return 'candidateObservers';
    }

    public function attach(CandidateObserverInterface $observer): void
    {
        $observers = $this->getAttribute($this->observerCollectionName()) ?? collect();
        $observers->put($observer->candidate()->id, $observer);
        $this->setAttribute($this->observerCollectionName(), $observers);
    }

    public function detach(CandidateObserverInterface $observer): void
    {
        $observers = $this->getAttribute($this->observerCollectionName()) ?? collect();
        $observers->forget($observer->candidate()->id);
        $this->setAttribute($this->observerCollectionName(), $observers);
    }

    public function notifyObservers(string $message, string $type): void
    {
        /** @var Collection<int, CandidateObserverInterface> $observers */
        $observers = $this->getAttribute($this->observerCollectionName()) ?? collect();

        $observers->each(fn (CandidateObserverInterface $observer) => $observer->update($this, $message, $type));
    }
}
