<?php

namespace CaseStoreBundle\Entity;

use CaseStoreBundle\CaseStoreBundle;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="CaseStoreBundle\Repository\CaseStudyDocumentRepository")
 * @ORM\Table(name="case_study_document", uniqueConstraints={@ORM\UniqueConstraint(name="public_id", columns={"case_study_id", "public_id"})})
 * @ORM\HasLifecycleCallbacks
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyDocument
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\CaseStudy")
     * @ORM\JoinColumn(name="case_study_id", referencedColumnName="id", nullable=false)
     */
    private $caseStudy;

    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     */
    private $publicId;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(name="original_file_name", type="string", length=255, nullable=false)
     */
    private $originalFileName;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=false)
     */
    private $path;

    /**
     * @ORM\Column(name="mime", type="string", length=255, nullable=false)
     */
    private $mime;


    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="CaseStoreBundle\Entity\User")
     * @ORM\JoinColumn(name="added_by_id", referencedColumnName="id", nullable=false)
     */
    private $addedBy;

    /**
     * @ORM\Column(name="added_at", type="datetime", nullable=false)
     */
    private $addedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="is_case_study_users_only", type="boolean", nullable=false, options={"default" = 0})
     */
    private $isCaseStudyUsersOnly = false;


    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'documents';
    }

    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->file) {
            return;
        }

        $this->originalFileName = $this->file->getClientOriginalName();

        // find new file name
        $newFileName = null;
        while (!$newFileName || file_exists($this->getUploadRootDir().DIRECTORY_SEPARATOR.$newFileName)) {
            $newFileName =  CaseStoreBundle::createKey(1,100);
        }

        // set mime. Must do this before moving file!
        $this->mime = $this->file-> getMimeType();

        // move takes the target directory and then the
        // target filename to move to
        $this->file->move(
            $this->getUploadRootDir(),
            $newFileName
        );

        // set the path property to the filename where you've saved the file
        $this->path = $newFileName;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCaseStudy()
    {
        return $this->caseStudy;
    }

    /**
     * @param mixed $caseStudy
     */
    public function setCaseStudy($caseStudy)
    {
        $this->caseStudy = $caseStudy;
    }


    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }



    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param mixed $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return mixed
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @return mixed
     */
    public function getAddedAt()
    {
        return $this->addedAt;
    }

    /**
     * @param mixed $addedAt
     */
    public function setAddedAt($addedAt)
    {
        $this->addedAt = $addedAt;
    }






    public function isImage() {
        return in_array($this->mime, array('image/png'));
    }

    /**
     * @return mixed
     */
    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    /**
     * @param mixed $originalFileName
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;
    }

    /**
     * @return mixed
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param mixed $publicId
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * @return string
     */
    public function getIsCaseStudyUsersOnly()
    {
        return $this->isCaseStudyUsersOnly;
    }

    /**
     * @param string $isCaseStudyUsersOnly
     */
    public function setIsCaseStudyUsersOnly($isCaseStudyUsersOnly)
    {
        $this->isCaseStudyUsersOnly = $isCaseStudyUsersOnly;
    }



    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->addedAt = new \DateTime("", new \DateTimeZone("UTC"));
    }

}
