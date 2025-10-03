# Con-Paint

A web application that lets you create custom GitHub contribution patterns by drawing on an interactive contribution graph. This tool generates a Git repository with backdated commits to match your selected pattern.

**Live Demo:** [https://con-paint.onrender.com](https://con-paint.onrender.com)

## Important Disclaimer

**This tool is created purely for educational and entertainment purposes.** 

Please DO NOT use this tool to:
- Create fake contributions to deceive others
- Misrepresent your actual coding activity
- Inflate your GitHub profile artificially

GitHub contributions should reflect genuine work and learning. Use this tool responsibly and ethically - it's meant for fun experiments, testing, and learning about Git internals, not for deception.

## Features

- Interactive contribution graph visualization
- Click and drag to select dates
- Customize year, commits per day, and timezone
- Generate a Git repository with backdated commits
- Download as a ZIP file ready to push to GitHub
- Mobile-responsive design
- Keyboard shortcuts (ESC to clear selection)

## How It Works

1. **Draw Your Pattern**: Click and drag on the contribution graph to select the dates you want to have commits
2. **Configure Settings**: 
   - Enter your GitHub username (the name used in `git config --global user.name`)
   - Enter your email (the email used in `git config --global user.email`)
   - Select the year
   - Set commits per day (1-10)
   - Choose your timezone
3. **Generate**: Click the generate button to create a ZIP file containing a Git repository
4. **Upload to GitHub**: 
   - Extract the ZIP file
   - Navigate to the repository folder
   - Create a new repository on GitHub
   - Push the commits using the provided commands

## Usage Instructions

### On the Website

1. Visit [https://con-paint.onrender.com](https://con-paint.onrender.com)
2. Fill in the form with your information:
   - **GitHub Username**: Your Git username (not your GitHub account username, but the name from `git config --global user.name`)
   - **Email**: Your Git email address (from `git config --global user.email`)
   - **Year**: The year you want to create commits for
   - **Commits per Day**: How many commits to create for each selected date
   - **Timezone**: Leave it on the default (UTC) setting. If you notice any errors or misrepresentation of dates on your contribution graph, try changing the timezone to match your location using the "Use Local Timezone" button
3. Click and drag on the contribution graph to select dates
4. Click "Generate & Download Repo" to download your repository

### Pushing to GitHub

After downloading and extracting the ZIP file:

```bash
cd repo
git remote add origin https://github.com/yourusername/your-repo-name.git
git push -u origin main --force
```

Make sure the repository exists on GitHub before pushing.

## Technical Stack

- **Backend**: PHP 8.1
- **Server**: Apache
- **Containerization**: Docker
- **Frontend**: Vanilla JavaScript, HTML5, CSS3
- **Version Control**: Git

## Local Development

### Prerequisites

- Docker and Docker Compose

### Running Locally

1. Clone the repository:
```bash
git clone https://github.com/yourusername/con-paint.git
cd con-paint
```

2. Build and run with Docker:
```bash
docker build -t con-paint .
docker run -p 8080:80 con-paint
```

3. Open your browser and navigate to `http://localhost:8080`

### File Structure

```
con-paint/
├── index.php          # Main UI and frontend logic
├── generate.php       # Backend processing and ZIP generation
├── Dockerfile         # Docker configuration
└── README.md          # Documentation
```

## How the Git Magic Works

The application creates a Git repository with backdated commits using the `--date` flag:

```bash
git commit --allow-empty -m 'Commit on YYYY-MM-DD' --date='DATE_STRING'
```

Each commit is empty (no file changes) but appears on your contribution graph because GitHub uses the commit date to populate the graph. The commits are spaced by 1 minute intervals to avoid timestamp collisions.

## Security Notes

- Input sanitization is performed on all user inputs
- Shell arguments are escaped using `escapeshellarg()`
- Temporary files are cleaned up after generation
- No persistent storage of user data

## Browser Compatibility

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open source and available under the MIT License.

## Ethical Use Reminder

Remember: Your GitHub profile should represent your real work and contributions. This tool is for experimentation and learning about Git internals. Using it to misrepresent your skills or activity is dishonest and can harm your professional reputation.

Use responsibly and have fun learning!

## Support

For issues, questions, or suggestions, please open an issue on the GitHub repository.

---

**Note**: The username and email you enter should match your Git configuration (`git config --global user.name` and `git config --global user.email`), not necessarily your GitHub account username.
