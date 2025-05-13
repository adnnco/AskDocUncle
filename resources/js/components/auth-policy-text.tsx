
export function AuthPolicyText(){
    return (
        <div className="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary  ">
            By clicking continue, you agree to our <a href={route('terms')}>Terms of Service</a>{" "}
            and <a href={route('privacy')}>Privacy Policy</a>.
        </div>
    );
}
