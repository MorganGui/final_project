<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subject $subject = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    // id
    public function getId(): ?int
    {
        return $this->id;
    }

    // content
    public function getContent(): ?string
    {
        return $this->content;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    // user
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // subject
    public function getSubject(): ?Subject
    {
        return $this->subject;
    }
    public function setSubject(?Subject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    // created_at
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }





     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachment;

    /**
     * @var UploadedFile|null
     */
    private $attachmentFile;

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getAttachmentFile(): ?UploadedFile
    {
        return $this->attachmentFile;
    }

    public function setAttachmentFile(?UploadedFile $attachmentFile): self
    {
        $this->attachmentFile = $attachmentFile;
        if ($attachmentFile instanceof UploadedFile) {
            $this->setAttachment($attachmentFile->getClientOriginalName());
        }

        return $this;
    }
}