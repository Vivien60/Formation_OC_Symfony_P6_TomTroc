Link to MCD :
https://www.mermaidchart.com/app/projects/c5f8f79c-a7fb-41f2-b76d-bf89ac48798b/diagrams/cf6af6ff-2967-45dc-8edf-0712f0f991d9/version/v0.1/edit
```Mermaid
erDiagram
    direction TB
    BOOK_COPY {
        int id  ""  
        date created_at  ""  
        string title  ""  
        string author  ""  
        string availability_status  ""  
        string image  ""  
    }

    USER {
        int id  ""  
        string name  ""  
        string email  ""  
        date created_at  ""  
    }

    THREAD {
        int id  ""  
        date created_at  ""  
    }

    MESSAGE {
        int rank PK ""  
        int thread_id FK, PK ""  
        date created_at  ""  
        string content  ""  
    }

    USER||--o{MESSAGE:"writes"
    MESSAGE}o--||THREAD:"contains"
    THREAD}o--|{USER:"participates"
    USER||--o{BOOK_COPY:"own"
```