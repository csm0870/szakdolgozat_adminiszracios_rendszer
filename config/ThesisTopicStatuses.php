<?php
    return[
        'ThesisTopicStatuses' => [
            'WaitingForStudentFinalize' => 1, //Hallgatói véglegesítésre vár
            'WaitingForInternalConsultantAcceptingOfThesisTopicBooking' => 2, //A témafoglalás a belső konzulens elfogadására vár
            'ThesisTopicBookingRejectedByInternalConsultant' => 3, //A témafoglalást a belső konzulens visszautasította
            'WaitingForStudentFinalizingOfThesisTopicBooking' => 4, //A témafoglalás a hallgató véglegesítésére vár
            'ThesisTopicBookingCanceledByStudent' => 5, //A témafoglalást a hallgató visszautasította
            'WaitingForInternalConsultantAcceptingOfThesisTopic' => 6, //A téma a belső konzulens döntére vár
            'ThesisTopicRejectedByInternalConsultant' => 7, //A téma elutasítva (belső konzulens)
            'WaitingForHeadOfDepartmentAcceptingOfThesisTopic' => 8, //Tanszékvezető döntésére vár
            'ThesisTopicRejectedByHeadOfDepartment' => 9, //Téma elutasítva (tanszékvezető)
            'ProposalForAmendmentOfThesisTopicAddedByHeadOfDepartment' => 10, //Tanszékvezető módosítási javaslatot adott
            'WaitingForCheckingExternalConsultantSignatureOfThesisTopic' => 11, //Külső konzulensi aláírás ellenőrzésre vár
            'ThesisTopicRejectedByExternalConsultant' => 12, //Téma elitasítva (külső konzulens)
            'ThesisTopicAccepted' => 13, //Téma elfogadva
            'FirstThesisSubjectFailedWaitingForHeadOfDepartmentDecision' => 14, //Első diplomakurzus sikertelen, tanszékvezető döntésére vár
            'ThesisTopicRejectedByHeadOfDepartmentCauseOfFirstThesisSubjectFailed' => 15, //Téma elutasítva (első diplomakurzus sikertelen)
            'FirstThesisSubjectSucceeded' => 16, //Első diplomakurzus teljesítve
            'ThesisSupplementUploadable' => 17, //A szakdolgozat/diplomamunka a formai követelményeknek megfelelt, feltölthető
            'WaitingForStudentFinalizeOfUploadOfThesisSupplement' => 18, //Szakdolgozat feltöltve, hallgató véglegesítésére vár
            'WaitingForCheckingOfThesisSupplements' => 19, //Szakdolgozat feltöltve, ellenőrzésre vár
            'ThesisSupplementsRejected' => 20, //Szakdolgozat mellékletek elutasítva
            'WaitingForDesignationOfReviewerByInternalConsultant' => 21, //Szakdolgozat mellékletek elfogadva, bíráló kijelölésére vár
            'WaitingForDesignationOfReviewerByHeadOfDepartment' => 22, //A dolgozat bírálója kijelölve, tanszékvezető ellenőrzésére vár
            'WatingForSendingToReview' => 23, //A dolgozat bírálója kijelölve, bírálatra küldésre vár
            'UnderReview' => 24, //A dolgozat bírálat alatt
            'Reviewed' => 25, //A dolgozat bírálva
            'ThesisAccepted' => 26 //A dolgozat elfogadva
        ]
    ];
?>