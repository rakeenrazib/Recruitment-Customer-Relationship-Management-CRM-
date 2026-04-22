@component('mail::message')
# Interview Scheduled

Hi **{{ $candidateName }}**,

Great news! Your application for the position of **{{ $jobTitle }}** at **{{ $company }}** has progressed to the **interview stage**.

Our recruitment team will be in touch shortly with the interview details including the date, time, and format.

@component('mail::button', ['url' => config('app.url')])
View Your Application
@endcomponent

Good luck!

**{{ $company }} Recruitment Team**
@endcomponent
