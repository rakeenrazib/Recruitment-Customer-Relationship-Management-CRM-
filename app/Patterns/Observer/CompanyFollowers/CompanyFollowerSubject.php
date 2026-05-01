<?php

namespace App\Patterns\Observer\CompanyFollowers;

class CompanyFollowerSubject
{
    /**
     * This array is the Subject's collection of observers.
     * It is one of the main characteristics shown in the lecture pseudocode.
     *
     * @var array<int, ObserverInterface>
     */
    private array $observers = [];

    private string $message = '';

    private string $notificationType = '';

    private ?string $subjectType = null;

    private ?int $subjectId = null;

    /**
     * registerObserver() matches the exact naming/style taught in the slides.
     */
    public function registerObserver(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * unregisterObserver() removes an observer from the Subject list.
     */
    public function unregisterObserver(ObserverInterface $observer): void
    {
        $this->observers = array_values(array_filter(
            $this->observers,
            fn (ObserverInterface $registeredObserver) => $registeredObserver !== $observer
        ));
    }

    /**
     * notifyObservers() loops through every registered observer and calls the
     * shared notification method.
     */
    public function notifyObservers(): void
    {
        foreach ($this->observers as $observer) {
            $observer->notify();
        }
    }

    /**
     * The Subject stores the state that observers should react to.
     * This is similar to the weather-station example from the lecture slides.
     */
    public function setNotificationDetails(string $message, string $notificationType, ?string $subjectType = null, ?int $subjectId = null): void
    {
        $this->message = $message;
        $this->notificationType = $notificationType;
        $this->subjectType = $subjectType;
        $this->subjectId = $subjectId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getNotificationType(): string
    {
        return $this->notificationType;
    }

    public function getSubjectType(): ?string
    {
        return $this->subjectType;
    }

    public function getSubjectId(): ?int
    {
        return $this->subjectId;
    }
}
