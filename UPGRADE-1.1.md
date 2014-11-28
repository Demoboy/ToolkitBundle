Changes:

    \KMJ\ToolkitBundle\Entity\Address:
        removed functions:
            setGeoCoordinates
            cloneAddress

        address property was renamed to street
            This also changes getAddress and setAddress to getStreet and setStreet
        address2 proper was renamed to unit
            This also changes getAddress2 and setAddress2 to getUnit and setUnit

    \KMJ\ToolkitBundle\Entity\State:
        taxRate was removed


        